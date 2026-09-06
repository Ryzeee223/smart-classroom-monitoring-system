//ESP8266 5-6-7 on
//switch 1-2 on 

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClientSecure.h>

const char* ssid = "RZE-0020(2.4)";
const char* password = "RiZe212004";

enum ScanMode { SETTINGS_MODE, ATTENDANCE_MODE };
const ScanMode scanMode = SETTINGS_MODE;

const char* settingsUrl = "https://rfinside.vercel.app/api/rfid-scan";
const char* attendanceUrl = "https://rfinside.vercel.app/api/attendance-scan";

const char* getScanUrl() {
  return (scanMode == SETTINGS_MODE) ? settingsUrl : attendanceUrl;
}

void setup() {
  Serial.begin(115200); 

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi connected");
}

void loop() {
  if (Serial.available() > 0) {
    String rfidData = Serial.readStringUntil('\n');
    rfidData.replace("\r", ""); // Remove hidden carriage returns
    rfidData.trim();            

    if (rfidData.length() > 0 && WiFi.status() == WL_CONNECTED) {
      WiFiClientSecure client;
      client.setInsecure(); // Bypass SSL verification

      HTTPClient http;
      const char* scanUrl = getScanUrl();

      if (http.begin(client, scanUrl)) {
        // Vercel deployment and modern API fixes:
        http.addHeader("User-Agent", "ESP8266-RFID-Client");
        http.addHeader("Content-Type", "application/json");

        // Format as standard JSON payload
        String jsonPayload = "{\"uid\":\"" + rfidData + "\"}";
        
        int httpResponseCode = http.POST(jsonPayload);

        Serial.print("Sent Payload: ");
        Serial.println(jsonPayload);
        Serial.print("HTTP Response Code: ");
        Serial.println(httpResponseCode);

        // Read and display server output for debugging
        if (httpResponseCode > 0) {
          String response = http.getString();
          Serial.print("Vercel Server Response: ");
          Serial.println(response);
        } else {
          Serial.print("Error: ");
          Serial.println(http.errorToString(httpResponseCode).c_str());
        }

        http.end();
      } else {
        Serial.println("Unable to connect to host");
      }
    }
  }
}