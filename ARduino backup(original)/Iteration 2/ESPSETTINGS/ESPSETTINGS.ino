#include <WiFi.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <SPI.h>
#include <MFRC522.h>
#include <LiquidCrystal_I2C.h>

// --- Pin Definitions for ESP32 ---
#define SS_PIN    5     // MFRC522 SDA/SS
#define RST_PIN   27    // MFRC522 RST
#define BUZZER_PIN 4    // Buzzer pin

// --- Network & API Configuration ---
const char* ssid = "Our2.4G";
const char* password = "RZE-2004";

const char* settingsUrl = "https://rfinside.vercel.app/api/rfid-scan";
const char* room = "CC101";

// --- Hardware Instances ---
LiquidCrystal_I2C lcd(0x27, 20, 2);
MFRC522 mfrc522(SS_PIN, RST_PIN);

// --- Function to Handle HTTP POST Scans ---
void postScan(const char* scanUrl, const String& jsonPayload) {
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

      // If the server returns a custom message, show it briefly on the LCD
      if (responseBody.length() > 0) {
        int separator = responseBody.indexOf('|');
        lcd.clear();
        if (separator >= 0) {
          lcd.setCursor(0, 0);
          lcd.print(responseBody.substring(0, separator));
          lcd.setCursor(0, 1);
          lcd.print(responseBody.substring(separator + 1));
        } else {
          lcd.setCursor(0, 0);
          lcd.print("Response:");
          lcd.setCursor(0, 1);
          lcd.print(responseBody.substring(0, 20)); // Truncate to fit screen width
        }
        delay(3000);
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

  // Initialize I2C with explicit ESP32 pins (SDA=21, SCL=22)
  Wire.begin(21, 22);
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("Connecting WiFi...");

  // Connect to Wi-Fi
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
  }

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("WiFi Connected!");
  delay(1500);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Scan RFID Card..");
}

void loop() {
  checkWiFiConnection();
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

  // Visual feedback on LCD
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Card received");
  

  // Build JSON Payload
  String jsonPayload = "{\"uid\":\"" + cardUID + "\"}";

  // Send request to settings endpoint
  postScan(settingsUrl, jsonPayload);

  mfrc522.PICC_HaltA();

  // Cooldown delay before resetting display prompt
  delay(2000);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Scan RFID Card..");
}