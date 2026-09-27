// DIP SWITCHES FOR RUNTIME (Normal Operation):
// 1-2 ON to go live
// ESP8366 5-6-7

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClientSecure.h>
#include <ESPmDNS.h>

const char* ssid = "Our2.4G";
const char* password = "RZE-2004";

enum ScanMode { SETTINGS_MODE, ATTENDANCE_MODE };
const ScanMode scanMode = SETTINGS_MODE;

// always check me before sketching
//offline
// const char* settingsUrl = "192.168.100.2:8000/api/rfid-scan"
// const char* attendanceUrl = "192.168.100.2:8000/api/attendance-scan"

// online
const char* settingsUrl = "https://rfinside.vercel.app/api/rfid-scan";
const char* attendanceUrl = "https://rfinside.vercel.app/api/attendance-scan";
const char* lcdUrl = "https://rfinside.vercel.app/api/live-classrooms?room=";

// Room definition
const char* room = "CC101";

const char* getScanUrl() {
  return (scanMode == SETTINGS_MODE) ? settingsUrl : attendanceUrl;
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
      WiFiClientSecure client;
      client.setInsecure(); // Bypass SSL verification for Vercel HTTPS

      HTTPClient http;
      const char* scanUrl = getScanUrl();

      if (http.begin(client, scanUrl)) {
        http.addHeader("User-Agent", "ESP8266-RFID-Client");
        http.addHeader("Content-Type", "application/json");

        // Included room identifier in JSON payload
        String jsonPayload = "{\"uid\":\"" + rfidData + "\",\"room\":\"" + String(room) + "\"}";
        
        int httpResponseCode = http.POST(jsonPayload);

        if (httpResponseCode > 0) {
          http.getString();
        }

        http.end();
      }
    }
  }
}