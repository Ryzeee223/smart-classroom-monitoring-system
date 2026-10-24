#include <WiFi.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <SPI.h>
#include <MFRC522.h>

// --- Pin Definitions for ESP32 ---
#define SS_PIN    5     // MFRC522 SDA/SS
#define RST_PIN   27    // MFRC522 RST
#define BUZZER_PIN 25   // Buzzer pin

// --- Network & API Configuration ---
const char* ssid     = "Our2.4G";
const char* password = "RZE-2004";

const char* settingsUrl = "https://rfinside.vercel.app/api/rfid-scan";
const char* room = "CC101";

MFRC522 mfrc522(SS_PIN, RST_PIN);

// --- Wi-Fi Monitoring Global Variables ---
unsigned long lastWifiCheck = 0;
bool wasConnected = true;

// --- Function to Monitor and Reconnect Wi-Fi ---
void checkWiFiConnection() {
  if (millis() - lastWifiCheck < 10000) return;
  lastWifiCheck = millis();

  if (WiFi.status() != WL_CONNECTED) {
    if (wasConnected) {
      Serial.println("[WiFi] Connection lost! Reconnecting...");
      wasConnected = false;
    }
    
    WiFi.disconnect();
    WiFi.begin(ssid, password);
    
    unsigned long startAttemptTime = millis();
    while (WiFi.status() != WL_CONNECTED && millis() - startAttemptTime < 8000) {
      delay(500);
    }

    if (WiFi.status() == WL_CONNECTED) {
      wasConnected = true;
      Serial.println("[WiFi] Reconnected successfully!");
    }
  }
}

// --- Function to Handle Settings HTTP POST Scans ---
void postScan(const String& jsonPayload) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[Error] No WiFi Connection. Cannot send scan.");
    return;
  }

  WiFiClientSecure client;
  client.setInsecure(); // Bypass SSL certificate validation for Vercel

  HTTPClient http;
  if (http.begin(client, settingsUrl)) {
    http.addHeader("User-Agent", "ESP32-RFID-Client");
    http.addHeader("Content-Type", "application/json");

    int responseCode = http.POST(jsonPayload);
    if (responseCode > 0) {
      String responseBody = http.getString();
      responseBody.replace("\r", "");
      responseBody.trim();

      Serial.print("[Server Response]: ");
      Serial.println(responseBody);
    } else {
      Serial.print("[HTTP Error]: ");
      Serial.println(responseCode);
    }
    http.end();
  }
}

void setup() {
  Serial.begin(115200);
  delay(100); // Quick stabilization delay

  // Initialize hardware
  SPI.begin();
  mfrc522.PCD_Init();

  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(BUZZER_PIN, LOW);

  Serial.println("\n[System] Booting Settings Module...");

  // Connect to Wi-Fi
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  unsigned long startAttemptTime = millis();
  while (WiFi.status() != WL_CONNECTED && millis() - startAttemptTime + 10000 < millis()) {
    delay(500);
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("[WiFi] Connected!");
    Serial.print("[IP Address]: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("[WiFi] Connection slow/failed, will retry in background loop.");
  }

  Serial.println("[System] Ready. Scan RFID Card..");
}

void loop() {
  // Periodically check Wi-Fi connection health in the background
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

  Serial.print("[RFID] Card Detected: ");
  Serial.println(cardUID);

  // Audio feedback
  tone(BUZZER_PIN, 2000, 100);

  // Build JSON Payload
  String jsonPayload = "{\"uid\":\"" + cardUID + "\",\"room\":\"" + String(room) + "\"}";

  // Send request to settings endpoint
  postScan(jsonPayload);

  mfrc522.PICC_HaltA();

  // Short cooldown before next scan read
  delay(1000);
}