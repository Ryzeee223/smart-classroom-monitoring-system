// DIP SWITCHES FOR RUNTIME (Normal Operation):
// 1-2 ON to go live
// ESP8366 5-6-7

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClientSecure.h>
#include <ESP8266mDNS.h>

const char* ssid = "Our2.4G";
const char* password = "RZE-2004";

enum ScanMode { SETTINGS_MODE, ATTENDANCE_MODE, BOTH_MODE };
const ScanMode scanMode = BOTH_MODE;

// online
const char* settingsUrl = "https://rfinside.vercel.app/api/rfid-scan";
const char* attendanceLcdUrl = "https://rfinside.vercel.app/api/attendance-scan?format=lcd";
const char* lcdUrl = "https://rfinside.vercel.app/api/live-classrooms?room=";

// Room definition
const char* room = "CC101";

void postScan(const char* scanUrl, const String& jsonPayload, bool showAttendance = false) {
  WiFiClientSecure client;
  client.setInsecure();

  HTTPClient http;
  if (http.begin(client, scanUrl)) {
    http.addHeader("User-Agent", "ESP8266-RFID-Client");
    http.addHeader("Content-Type", "application/json");

    int responseCode = http.POST(jsonPayload);
    if (responseCode > 0) {
      String responseBody = http.getString();
      responseBody.replace("\r", "");
      responseBody.trim();

      if (showAttendance && responseBody.length() > 0) {
        Serial.print("ATT:");
        Serial.println(responseBody);
      }
    }
    http.end();
  }
}

unsigned long lastLcdPoll = 0;

void pollLcdDisplay() {
  if (WiFi.status() != WL_CONNECTED || millis() - lastLcdPoll < 5000) {
    return;
  }
  lastLcdPoll = millis();

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
        Serial.print("LCD:");
        Serial.println(payload);
      }
    }
    http.end();
  }
}

void setup() {
  Serial.begin(115200); 

  WiFi.mode(WIFI_STA); // Ensure standard station mode
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    // Serial output skipped during setup to avoid sending clutter to ATmega
  }
}

void loop() {
  pollLcdDisplay();

  if (Serial.available() > 0) {
    String packet = Serial.readStringUntil('\n');
    packet.replace("\r", "");
    packet.trim();

    if (!packet.startsWith("RFID:")) {
      return;
    }

    String rfidData = packet.substring(5);
    rfidData.trim();

    if (rfidData.length() > 0 && WiFi.status() == WL_CONNECTED) {
      String jsonPayload = "{\"uid\":\"" + rfidData + "\",\"room\":\"" + String(room) + "\"}";

      if (scanMode == SETTINGS_MODE || scanMode == BOTH_MODE) {
        postScan(settingsUrl, jsonPayload);
      }
      if (scanMode == ATTENDANCE_MODE || scanMode == BOTH_MODE) {
        postScan(attendanceLcdUrl, jsonPayload, true);
      }
    }
  }
}