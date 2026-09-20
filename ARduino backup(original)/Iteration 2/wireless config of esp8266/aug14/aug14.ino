// DIP SWITCHES FOR RUNTIME (Normal Operation):
// 1-2 ON to go live
// ESP8366 5-6-7

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClientSecure.h>
#include <ArduinoJson.h>

const char* ssid = "Our2.4G";
const char* password = "RZE-202004";

enum ScanMode { SETTINGS_MODE, ATTENDANCE_MODE };
const ScanMode scanMode = SETTINGS_MODE;

const char* settingsUrl = "https://rfinside.vercel.app/api/rfid-scan";
const char* attendanceUrl = "https://rfinside.vercel.app/api/attendance-scan";

// Room definition
const char* room = "CC101";

const char* getScanUrl() {
  return (scanMode == SETTINGS_MODE) ? settingsUrl : attendanceUrl;
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
  if (Serial.available() > 0) {
    String rfidData = Serial.readStringUntil('\n');
    rfidData.replace("\r", ""); // Remove hidden carriage returns
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
          // Do not send the JSON response back to the ATmega as scanner data.
          http.getString();
          Serial.print("HTTP:");
          Serial.println(httpResponseCode);
        } else {
          Serial.print("ERROR:");
          Serial.println(httpResponseCode);
        }

        http.end();
      }
    }
  }
}