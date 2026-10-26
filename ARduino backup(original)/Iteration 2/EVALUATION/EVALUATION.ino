#include <WiFi.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <SPI.h>
#include <MFRC522.h>
#include <Adafruit_GFX.h>
#include <Adafruit_ILI9341.h>

// --- Pin Definitions for ESP32 ---
#define SS_PIN    5     // MFRC522 SDA/SS
#define RST_PIN   27    // MFRC522 RST
#define BUZZER_PIN 25   // Buzzer pin (set to 25 to avoid conflict with TFT CS on GPIO 4)

// --- TFT Display Pin Configuration (Software SPI) ---
#define TFT_CS   4
#define TFT_DC   21
#define TFT_RST  22
#define TFT_MOSI 13
#define TFT_SCLK 14
#define TFT_MISO 12

Adafruit_ILI9341 tft = Adafruit_ILI9341(TFT_CS, TFT_DC, TFT_MOSI, TFT_SCLK, TFT_RST, TFT_MISO);

// --- Network & API Configuration ---
const char* ssid     = "Our2.4G";
const char* password = "RZE-2004";

// const char* ssid     = "Ryze";
// const char* password = "Rize212004";

// const char* ssid     = "ROUGEASSASIN 0491";
// const char* password = "test123456";

// const char* ssid     = "Hexx";
// const char* password = "12345678";

//online
const char* settingsUrl = "https://rfinside.vercel.app/api/rfid-scan";
const char* attendanceLcdUrl = "https://rfinside.vercel.app/api/attendance-scan?format=lcd";
const char* lcdUrl = "https://rfinside.vercel.app/api/live-classrooms?room=";

// local
// const char* settingsUrl ="http://localhost:8000/api/rfid-scan";
// const char* attendanceLcdUrl = "http://localhost:8000/api/check-latest-attendance";
// const char* lcdUrl = "http://localhost:8000/api/live-classrooms";


const char* room = "CC101";

// --- Scan Mode Configuration (Choose: SETTINGS_MODE, ATTENDANCE_MODE, or BOTH_MODE) ---
enum ScanMode { SETTINGS_MODE, ATTENDANCE_MODE, BOTH_MODE };
const ScanMode scanMode = BOTH_MODE; // Handles both settings and attendance

MFRC522 mfrc522(SS_PIN, RST_PIN);

unsigned long lastWifiCheck = 0;
bool wasConnected = true;

unsigned long attendanceDisplayUntil = 0;
unsigned long lastLcdPoll = 0;
String lastClassDisplayPayload = "";
bool classDisplayNeedsRefresh = true;

// --- Helper Function to Update TFT Text Layout ---
void updateTftText(String line1, String line2, uint16_t textColor = ILI9341_WHITE) {
  classDisplayNeedsRefresh = true;
  tft.fillScreen(ILI9341_BLACK);
  
  // Line 1
  tft.setCursor(10, 30);
  tft.setTextColor(textColor);
  tft.setTextSize(2);
  tft.println(line1);

  // Line 2
  tft.setCursor(10, 80);
  tft.setTextColor(ILI9341_YELLOW);
  tft.setTextSize(2);
  tft.println(line2);

  // Software Mask: Permanently covers any bottom/edge static noise strip
  tft.fillRect(0, 220, 320, 20, ILI9341_BLACK);
}

void updateTftLines(String lines[5]) {
  tft.fillScreen(ILI9341_BLACK);
  tft.setTextSize(2);

  for (int i = 0; i < 5; i++) {
    tft.setCursor(10, 12 + (i * 42));
    tft.setTextColor(i == 3 ? ILI9341_YELLOW : ILI9341_WHITE);
    tft.println(lines[i]);
  }

  tft.fillRect(0, 220, 320, 20, ILI9341_BLACK);
}

// --- Function to Monitor and Reconnect Wi-Fi ---
void checkWiFiConnection() {
  // Check every 10 seconds to avoid blocking loop operations
  if (millis() - lastWifiCheck < 10000) return;
  lastWifiCheck = millis();

  if (WiFi.status() != WL_CONNECTED) {
    if (wasConnected) {
      updateTftText("WiFi Lost!", "Reconnecting...");
      wasConnected = false;
    }
    
    WiFi.disconnect();
    WiFi.begin(ssid, password);
    
    unsigned long startAttemptTime = millis();
    // Try reconnecting for up to 8 seconds
    while (WiFi.status() != WL_CONNECTED && millis() - startAttemptTime < 8000) {
      delay(500);
    }

    if (WiFi.status() == WL_CONNECTED) {
      wasConnected = true;
      updateTftText("RFInsiDe Active", "Scan RFID Card..");
    }
  }
}

// --- Function to Handle HTTP POST Scans ---
void postScan(const char* scanUrl, const String& jsonPayload, bool showAttendance = false) {
  if (WiFi.status() != WL_CONNECTED) return;

  WiFiClientSecure client;
  client.setInsecure(); // Bypass SSL certificate validation for Vercel

  HTTPClient http;
  if (http.begin(client, scanUrl)) {
    http.addHeader("User-Agent", "ESP32-RFID-Client");
    http.addHeader("Content-Type", "application/json");

    int responseCode = http.POST(jsonPayload);
    if (responseCode > 0) {
      String responseBody = http.getString();
      responseBody.replace("\r", "");
      responseBody.trim();

      if (responseBody.length() > 0) {
        int separator = responseBody.indexOf('|');
        if (separator >= 0) {
          updateTftText(responseBody.substring(0, separator), responseBody.substring(separator + 1), ILI9341_GREEN);
        } else {
          updateTftText("Response:", responseBody.substring(0, 20), ILI9341_GREEN);
        }

        if (showAttendance) {
          attendanceDisplayUntil = millis() + 8000UL; // Hold attendance message for 8 seconds
        } else {
          delay(3000); // Standard delay for settings responses
        }
      }
    }
    http.end();
  }
}

// --- Function to Periodically Poll Live Classroom Data ---
void pollLcdDisplay() {
  if (WiFi.status() != WL_CONNECTED || millis() - lastLcdPoll < 5000) {
    return;
  }
  lastLcdPoll = millis();

  // Skip polling if an active attendance message is locked on screen
  if ((long)(millis() - attendanceDisplayUntil) < 0) {
    return;
  }

  WiFiClientSecure client;
  client.setInsecure();

  HTTPClient http;
  String lcdRequestUrl = String(lcdUrl) + room;
  if (http.begin(client, lcdRequestUrl)) {
    int responseCode = http.GET();
    if (responseCode > 0) {
      String payload = http.getString();
      payload.replace("\r", "");
      payload.trim();

      if (payload.length() > 0) {
        bool isClassUpdate = payload.startsWith("LCD:");
        String displayText = isClassUpdate ? payload.substring(4) : payload;
        String lines[5];
        int start = 0;
        bool hasFiveLines = true;

        for (int i = 0; i < 5; i++) {
          int separator = displayText.indexOf('|', start);
          if (i < 4 && separator < 0) {
            hasFiveLines = false;
            break;
          }

          int end = separator < 0 ? displayText.length() : separator;
          lines[i] = displayText.substring(start, end);
          start = end + 1;
        }

        if (hasFiveLines
            && (classDisplayNeedsRefresh || displayText != lastClassDisplayPayload)) {
          updateTftLines(lines);
          lastClassDisplayPayload = displayText;
          classDisplayNeedsRefresh = false;
        }
      }
    }
    http.end();
  }
}

void setup() {
  Serial.begin(115200);

  // Initialize hardware
  SPI.begin();
  mfrc522.PCD_Init();

  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(BUZZER_PIN, LOW);

  // Initialize TFT Display properly
  tft.begin();          
  tft.fillScreen(ILI9341_BLACK);
  tft.setRotation(2);   // Change rotation (0, 1, 2, or 3) if needed
  
  updateTftText("System Boot", "Connecting WiFi...");

  // Connect to Wi-Fi
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
  }

  updateTftText("Status:", "WiFi Connected!");
  delay(1000);
}

void loop() {
  checkWiFiConnection();
  
  // Poll background classroom info from server
  pollLcdDisplay();

  // Look for new RFID cards
  if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
    return;
  }

  // Format UID string to uppercase hex
  String cardUID = "";
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    if (mfrc522.uid.uidByte[i] < 0x10) cardUID += "0";
    cardUID += String(mfrc522.uid.uidByte[i], HEX);
  }
  cardUID.toUpperCase();

  // Audio feedback
  tone(BUZZER_PIN, 2000, 100);

  // Visual feedback on TFT
  updateTftText("Card received", "Checking server");

  // Build JSON Payload
  String jsonPayload = "{\"uid\":\"" + cardUID + "\",\"room\":\"" + String(room) + "\"}";

  // Send requests based on your chosen scan mode
  if (scanMode == SETTINGS_MODE || scanMode == BOTH_MODE) {
    postScan(settingsUrl, jsonPayload, false);
  }
  if (scanMode == ATTENDANCE_MODE || scanMode == BOTH_MODE) {
    postScan(attendanceLcdUrl, jsonPayload, true);
  }

  mfrc522.PICC_HaltA();

  // Cooldown delay before resetting display prompt (unless overridden by active message)
  delay(1000);
  if ((long)(millis() - attendanceDisplayUntil) >= 0) {
    updateTftText("RFInsiDe Active", "Scan RFID Card..");
  }
}