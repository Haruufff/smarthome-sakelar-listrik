#include <Wire.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <LiquidCrystal_I2C.h>
#include <NTPClient.h>
#include <WiFiUdp.h>
#include <ArduinoJson.h>
#include <PZEM004Tv30.h>
#include <SoftwareSerial.h>

const char* ssid = "HENI";
const char* password = "SaMaWa04";

// API
const char* postEnergy = "http://192.168.1.3:8080/api/monitoring/post-energy-data";
const char* getSwitches = "http://192.168.1.3:8080/api/switches";

// PZEM-004T Setup (SoftwareSerial)
SoftwareSerial pzemSerial(D7, D6); // RX=D7, TX=D6
PZEM004Tv30 pzem(pzemSerial);

// SSR PINS
const int SSR1_PIN = D3;
const int SSR2_PIN = D4;
const int SSR3_PIN = D5;
const int SSR4_PIN = D8;

unsigned long previousSendMillis = 0;
unsigned long previousSwitchCheckMillis = 0;
unsigned long previousLCDUpdateMillis = 0;
const long sendInterval = 5000; // Send data every 5 seconds
const long switchCheckInterval = 1000; // check switches every 1 second
const long lcdUpdateInterval = 2000; // Update LCD every 2 seconds

LiquidCrystal_I2C lcd(0x27, 16, 2);

WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "pool.ntp.org", 28800, 60000);
int lcdDisplayMode = 0;

void setup() {
  Serial.begin(9600);

  pzemSerial.begin(9600);
  Serial.println("PZEM-004T initialized");

  pinMode(SSR1_PIN, OUTPUT);
  pinMode(SSR2_PIN, OUTPUT);
  pinMode(SSR3_PIN, OUTPUT);
  pinMode(SSR4_PIN, OUTPUT);
  
  // Set all SSRs to OFF initially
  digitalWrite(SSR1_PIN, HIGH);
  digitalWrite(SSR2_PIN, HIGH);
  digitalWrite(SSR3_PIN, HIGH);
  digitalWrite(SSR4_PIN, HIGH);
  
  Wire.begin(D2, D1);  // SDA=D2, SCL=D1
  delay(100);

  lcd.begin();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("Sakelar Listrik");
  lcd.setCursor(3, 1);
  lcd.print("Smart Home");
  delay(3000);

  showConnection();
  delay(2000);

  connectToWiFi();

  if (WiFi.status() == WL_CONNECTED) {
    timeClient.begin();
    timeClient.update();
    Serial.println("NTP time synchronized");
    Serial.println("Current time: " + getFormattedDateTime());

    getSwitchStates();

    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("PZEM Reading...");
    delay(2000);
  }
}

void loop() {
  unsigned long currentMillis = millis();
  
  // Check WiFi connection
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi connection lost! Reconnecting...");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Disconnected!");
    lcd.setCursor(0, 1);
    lcd.print("Reconnecting...");
    
    connectToWiFi();
    
    if (WiFi.status() == WL_CONNECTED) {
      timeClient.begin();
      timeClient.update();
    }
  }
    
    // Send monitoring data
  if (currentMillis - previousSendMillis >= sendInterval) {
    previousSendMillis = currentMillis;
    
    if (WiFi.status() == WL_CONNECTED) {
      timeClient.update();
      sendMonitoringData();
    }
  }
  
  // Check switch ssr
  if (currentMillis - previousSwitchCheckMillis >= switchCheckInterval) {
    previousSwitchCheckMillis = currentMillis;
    
    if (WiFi.status() == WL_CONNECTED) {
      getSwitchStates();
    }
  }

  // Update LCD display
  if (currentMillis - previousLCDUpdateMillis >= lcdUpdateInterval) {
    previousLCDUpdateMillis = currentMillis;
    updateLCDDisplay();
  }

  delay(100);
}

void showConnection() {
  // SSID
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("SSID:");
  lcd.setCursor(0, 1);
  lcd.print(ssid);

  Serial.print("SSID: ");
  Serial.println(ssid);

  delay(2000);
  
  // Password
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Password:");
  lcd.setCursor(0, 1);
  lcd.print(password);

  Serial.print("Password: ");
  Serial.println(password);

  delay(2000);
}

void connectToWiFi() {
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Connecting to");
  lcd.setCursor(0, 1);
  lcd.print(ssid);
  
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 20) {
    delay(500);
    Serial.print(".");
    attempts++;
  }
  
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println();
    Serial.println("WiFi connected successfully!");
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());
    
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Connected!");
    lcd.setCursor(0, 1);
    lcd.print(WiFi.localIP());
    delay(3000);
  } else {
    Serial.println();
    Serial.println("Failed to connect.");
    
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Connection");
    lcd.setCursor(0, 1);
    lcd.print("Failed!");
    delay(2000);
  }
}

void updateLCDDisplay() {
  // Read PZEM data for LCD display
  float voltage = pzem.voltage();
  float current = pzem.current();
  float power = pzem.power();
  float energy = pzem.energy();
  float frequency = pzem.frequency();
  
  // Check if reading is valid
  if (isnan(voltage)) {
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("PZEM Error!");
    lcd.setCursor(0, 1);
    lcd.print("Check Wiring");
    return;
  }
  
  lcd.clear();
  
  // Rotate display between 3 modes
  switch(lcdDisplayMode) {
    case 0:
      // Display: Voltage and Current
      lcd.setCursor(0, 0);
      lcd.print("V:");
      lcd.print(voltage, 1);
      lcd.print("V");
      
      lcd.setCursor(0, 1);
      lcd.print("I:");
      lcd.print(current, 2);
      lcd.print("A");
      break;
      
    case 1:
      // Display: Power and Frequency
      lcd.setCursor(0, 0);
      lcd.print("P:");
      lcd.print(power, 1);
      lcd.print("W");
      
      lcd.setCursor(0, 1);
      lcd.print("F:");
      lcd.print(frequency, 1);
      lcd.print("Hz");
      break;
      
    case 2:
      // Display: Energy and Time
      lcd.setCursor(0, 0);
      lcd.print("Energy:");
      lcd.print(energy, 2);
      lcd.print("kWh");
      
      lcd.setCursor(0, 1);
      lcd.print(getFormattedDateTime().substring(11, 19)); // Show HH:MM:SS
      break;
  }
  
  // Cycle to next display mode
  lcdDisplayMode++;
  if (lcdDisplayMode > 2) {
    lcdDisplayMode = 0;
  }
}

void sendMonitoringData() {
  WiFiClient client;
  HTTPClient http;
  
  Serial.println("=================================");
  Serial.println("Reading PZEM-004T...");
  
  // Read REAL data from PZEM-004T
  float voltage = pzem.voltage();
  float current = pzem.current();
  float power = pzem.power();
  float energy = pzem.energy();
  float frequency = pzem.frequency();
  
  if (isnan(voltage)) voltage = 0;
  if (isnan(current)) current = 0;
  if (isnan(power)) power = 0;
  if (isnan(energy)) energy = 0;
  if (isnan(frequency)) frequency = 0;
  
  // Display readings on Serial
  Serial.println("PZEM Readings:");
  Serial.print("  Voltage: "); Serial.print(voltage); Serial.println(" V");
  Serial.print("  Current: "); Serial.print(current); Serial.println(" A");
  Serial.print("  Power: "); Serial.print(power); Serial.println(" W");
  Serial.print("  Energy: "); Serial.print(energy); Serial.println(" kWh");
  Serial.print("  Frequency: "); Serial.print(frequency); Serial.println(" Hz");
  
  int taxId = 1;
  
  // Get formatted datetime from NTP
  String datetime = getFormattedDateTime();
  
  // Create JSON payload
  StaticJsonDocument<256> doc;
  doc["voltage"] = voltage;
  doc["current"] = current;
  doc["power"] = power;
  doc["energy"] = energy;
  doc["frequency"] = frequency;
  doc["tax_id"] = taxId;
  doc["datetime"] = datetime;
  
  String jsonPayload;
  serializeJson(doc, jsonPayload);
  
  Serial.println("Sending data to API:");
  Serial.println(jsonPayload);
  
  // Send HTTP POST request
  http.begin(client, postEnergy);
  http.addHeader("Content-Type", "application/json");
  
  int httpResponseCode = http.POST(jsonPayload);
  
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("HTTP Response code: " + String(httpResponseCode));
    Serial.println("Response: " + response);
    Serial.println("=================================");
    
    if (httpResponseCode == 201 || httpResponseCode == 200) {
      Serial.println("✓ Data sent successfully!");
    }
  } else {
    Serial.print("Error code: ");
    Serial.println(httpResponseCode);
    Serial.println("Error: " + http.errorToString(httpResponseCode));
    Serial.println("=================================");
  }
  
  http.end();
}

String getFormattedDateTime() {
  // Get epoch time
  unsigned long epochTime = timeClient.getEpochTime();
  
  // Convert to time structure
  time_t rawtime = epochTime;
  struct tm * ti;
  ti = localtime(&rawtime);
  
  // Format: YYYY-MM-DD HH:MM:SS
  char buffer[20];
  sprintf(buffer, "%04d-%02d-%02d %02d:%02d:%02d",
          ti->tm_year + 1900,
          ti->tm_mon + 1,
          ti->tm_mday,
          ti->tm_hour,
          ti->tm_min,
          ti->tm_sec);
  
  return String(buffer);
}

void getSwitchStates() {
  WiFiClient client;
  HTTPClient http;
  
  Serial.println("----- Checking Switch States -----");
  
  http.begin(client, getSwitches);
  http.addHeader("Content-Type", "application/json");
  
  int httpResponseCode = http.GET();
  
  if (httpResponseCode == 200) {
    String response = http.getString();
    Serial.println("Switch API Response:");
    Serial.println(response);
    
    // Parse JSON response
    DynamicJsonDocument doc(2048);
    DeserializationError error = deserializeJson(doc, response);
    
    if (!error) {
      JsonArray switches = doc["data"].as<JsonArray>();
      
      for (JsonObject switchObj : switches) {
        int id = switchObj["id"];
        String name = switchObj["name"].as<String>();
        String state_status = switchObj["state_status"].as<String>();
        bool is_actived = switchObj["is_actived"];
        
        Serial.print("Switch ");
        Serial.print(id);
        Serial.print(" (");
        Serial.print(name);
        Serial.print("): ");
        Serial.print(state_status);
        Serial.print(" | Active: ");
        Serial.println(is_actived ? "Yes" : "No");
        
        // For LOW-level trigger: LOW = ON, HIGH = OFF
        int pinState;
        
        if (is_actived && state_status == "LOW") {
          pinState = LOW;  // Turn ON SSR (active LOW)
        } else {
          pinState = HIGH; // Turn OFF SSR
        }
        
        switch(id) {
          case 1:
            digitalWrite(SSR1_PIN, pinState);
            break;
          case 2:
            digitalWrite(SSR2_PIN, pinState);
            break;
          case 3:
            digitalWrite(SSR3_PIN, pinState);
            break;
          case 4:
            digitalWrite(SSR4_PIN, pinState);
            break;
        }
      }
      
      Serial.println("----------------------------------");
    } else {
      Serial.println("Failed to parse JSON");
    }
  } else {
    Serial.print("Error getting switches: ");
    Serial.println(httpResponseCode);
  }
  
  http.end();
}