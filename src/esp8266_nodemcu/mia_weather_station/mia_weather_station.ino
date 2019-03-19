/**
   LIBRARIES

   ESP8266HTTPClient / Markus Sattler
   https://github.com/esp8266/Arduino/tree/master/libraries/ESP8266HTTPClient
 * *
   ESP8266WebServer, ESP8266WiFi, WiFiClient / Ivan Grokhotkov
   https://github.com/esp8266/Arduino/tree/master/libraries/ESP8266WebServer
   https://github.com/esp8266/Arduino/tree/master/libraries/ESP8266WiFi
 * *
   WiFiManager / tzapu
   https://github.com/tzapu/WiFiManager
 * *
   DallasTemperature / Miles Burton & team
   https://github.com/milesburton/Arduino-Temperature-Control-Library
 * *
   Adafruit_Sensor, Adafruit_BME280, Adafruit_SI1145 / Adafruit
   https://github.com/adafruit/Adafruit_Sensor
   https://github.com/adafruit/Adafruit_BME280_Library
   https://github.com/adafruit/Adafruit_SI1145_Library
 * *
   OneWire / Paul Stoffregen & team
   https://github.com/PaulStoffregen/OneWire
 * *
   Wire, SPI / Arduino
   Standard libraries included in Arduino IDE.
*/
#include <ESP8266HTTPClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <DNSServer.h>
#include <WiFiManager.h>
#include <DallasTemperature.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME280.h>
#include <Adafruit_SI1145.h>
#include <OneWire.h>
#include <Wire.h>
#include <SPI.h>

/**
   CONFIGURATION

   host - website address or ip server address,
   key - authorization key,
   sealevelpressure - atmospheric pressure (hPa).
*/
const String host = "http://yourdomain.com/Collector.php";
const String key = "your_authorization_key";
const float sealevelpressure = 1013.25;

const int bmeSCL = D1;
const int bmeSDA = D2;
const int bmeCSB = D3;
const int bmeSDO = D4;
const int siSDA = D5;
const int siSCL = D6;
const int dsPIN = D7;
const int mqPIN = A0;

Adafruit_BME280 bme(bmeCSB, bmeSDA, bmeSDO, bmeSCL);
Adafruit_SI1145 si;

OneWire oneWire(dsPIN);
DallasTemperature dallas(&oneWire);


void setup() {
  Serial.begin(115200);
  delay(1000);
  Serial.println("WiFi manager started:");
  WiFiManager wifiManager;
  /**    
     CONFIGURATION AP (Access point)
     
     ssid (service set identifier) - Wi-Fi identifier,
     password - Wi-Fi password. 
  */
  wifiManager.autoConnect("MiaConfiguration", "theMia19");
  Serial.println("Connected! WiFi manager stopped.");
  Wire.begin(siSDA, siSCL);
  dallas.begin();
  Serial.println("");
  Serial.print("Sensor: DS18B20");
  if (dallas.getTempCByIndex(0) == -127) {
    delay(500);
    while (1);
  }
  Serial.println(" OK!");
  Serial.print("Sensor: BME280");
  if (!bme.begin()) {
    delay(500);
    while (1);
  }
  Serial.println(" OK!");
  Serial.print("Sensor: SI1145");
  if (!si.begin()) {
    delay(500);
    while (1);
  }
  Serial.println(" OK!");
}

void loop() {
  delay(30000);
  float temperature, humidity, pressure, altitude, vis, ir, uv, airQuality;
  /* Assign all readings from sensors to variables */
  dallas.requestTemperatures();
  temperature = dallas.getTempCByIndex(0);
  /** 
     To read the temperature from the BME280 we will use the following entry:
     temperature = bme.readTemperature();
  */
  humidity = bme.readHumidity();
  pressure = bme.readPressure() / 100.0F;
  altitude = bme.readAltitude(sealevelpressure);
  vis = si.readVisible();
  ir = si.readIR();
  uv = si.readUV() / 100.0;
  airQuality = analogRead(mqPIN);

  Serial.println("");
  Serial.println("======= SENDING  DATA =======");
  Serial.print("Temperature: ");
  Serial.print(temperature, 2);
  Serial.println(" °C");
  Serial.print("Humidity: ");
  Serial.print(humidity, 2);
  Serial.println(" %");
  Serial.print("Pressure: ");
  Serial.print(pressure, 2);
  Serial.println(" hPa");
  Serial.print("Altitude: ");
  Serial.print(altitude, 2);
  Serial.println(" m");
  Serial.print("VIS: ");
  Serial.println(vis, 0);
  Serial.print("IR: ");
  Serial.println(ir, 0);
  Serial.print("UV: ");
  Serial.println(uv, 2);
  Serial.print("Air pollution: ");
  Serial.print(airQuality, 2);
  Serial.println(" ppm");
  
  HTTPClient http;

  String postData, StringTemperature, StringHumidity, StringPressure,
         StringAltitude, StringVis, StringIr, StringUv, StringAirQuality;

  StringTemperature = String(temperature);
  StringHumidity = String(humidity);
  StringPressure = String(pressure);
  StringAltitude = String(altitude);
  StringVis = String(vis);
  StringIr = String(ir);
  StringUv = String(uv);
  StringAirQuality = String(airQuality);

  postData = "key=" + key + "&temperature=" + StringTemperature + "&humidity="
             + StringHumidity + "&pressure=" + StringPressure + "&uv=" + StringUv + "&ir="
             + StringIr + "&altitude=" + StringAltitude + "&airquality=" + StringAirQuality;

  /* Establishing a connection, setting the header */
  http.begin(host);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  /* Send required data */
  int httpCode = http.POST(postData);

  /* HTTP Status Code - 200 OK, response for successful HTTP request */
  if (httpCode == 200) {
    Serial.println("=========== SENT! ===========");
  }
  else {
    Serial.print("========== Error ");
    Serial.print(httpCode);
    Serial.println(" ==========");
  }

  /* Terminate connection */
  http.end();
  delay(270000);
}
