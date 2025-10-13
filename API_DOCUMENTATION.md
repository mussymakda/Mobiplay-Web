# MobiPlay Driver API Documentation

## 🚀 Complete API Documentation for Flutter Integration

This repository contains the complete API documentation for the **MobiPlay Driver App** - a location-based ad serving platform where drivers earn money by displaying targeted ads based on their GPS location.

---

## 📋 Quick Start Guide

### 1. **Import Postman Collection**
1. Open Postman
2. Click **Import** 
3. Select `MobiPlay_Driver_API_Collection.postman_collection.json`
4. Import `MobiPlay_Driver_Environment.postman_environment.json`

### 2. **Set Up Environment**
- Select "MobiPlay Driver API Environment" in Postman
- Base URL is pre-configured: `http://127.0.0.1:8000/api`
- Test credentials are included

### 3. **Start Laravel Server**
```bash
cd /path/to/mobiplay-web
php artisan serve
```

### 4. **Test Authentication**
- Use the "Driver Login" request
- Token will be automatically saved for subsequent requests

---

## 🏗️ API Architecture

### **Base URL**
```
http://127.0.0.1:8000/api
```

### **Authentication**
- **Type**: Bearer Token (Laravel Sanctum)
- **Header**: `Authorization: Bearer {token}`
- **Token obtained from**: POST `/driver/login`

---

## 📱 API Endpoints Overview

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/driver/login` | Driver authentication | ❌ |
| `POST` | `/driver/logout` | Revoke token | ✅ |
| `GET` | `/driver/profile` | Get driver info | ✅ |
| `POST` | `/driver/ads/request` | Get location-based ads | ✅ |
| `POST` | `/driver/ads/{id}/impression` | Record ad interaction | ✅ |
| `GET` | `/driver/earnings/summary` | Get earnings stats | ✅ |
| `GET` | `/qr/{qr_code}` | QR redirect & tracking | ❌ |

---

## 🔐 Authentication Flow

### **1. Driver Login**
```http
POST /api/driver/login
Content-Type: application/json

{
    "email": "testdriver@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "driver": {
            "id": 1,
            "name": "Test Driver",
            "email": "testdriver@example.com",
            "status": "online",
            "total_earnings": "0.00",
            "unpaid_amount": "0.00"
        },
        "token": "1|5ijVwgj72o8osmP6sWym9mqLatyKEsgjqC2Y5qzkf4a2b6ca"
    }
}
```

### **2. Use Token in Headers**
```http
Authorization: Bearer 1|5ijVwgj72o8osmP6sWym9mqLatyKEsgjqC2Y5qzkf4a2b6ca
```

---

## 📍 Location-Based Ad Serving

### **Smart Algorithm Features**
- ✅ **Geographic Targeting**: Ads filtered by GPS radius
- ✅ **Priority Sorting**: Package priority determines ad order  
- ✅ **Anti-Spam Filter**: Prevents showing same ads repeatedly
- ✅ **Budget Management**: Only shows ads with available budget
- ✅ **Distance Calculation**: Uses Haversine formula for accuracy

### **Request Location-Based Ads**
```http
POST /api/driver/ads/request
Authorization: Bearer {token}
Content-Type: application/json

{
    "latitude": 40.7128,
    "longitude": -74.0060
}
```

**Response Structure:**
```json
{
    "success": true,
    "message": "Ads retrieved successfully",
    "data": {
        "ads": [
            {
                "id": 1,
                "campaign_name": "Test Local Restaurant",
                "media": {
                    "type": "image",
                    "url": "http://127.0.0.1:8000/storage/test/restaurant-ad.jpg"
                },
                "qr_code": {
                    "url": "https://restaurant.example.com/menu",
                    "position": "top-right",
                    "redirect_url": "http://127.0.0.1:8000/api/qr/MXx..."
                },
                "location": {
                    "latitude": 40.7580,
                    "longitude": -73.9855,
                    "location_name": "Times Square Area",
                    "radius_miles": 5.0
                },
                "display_duration": 30,
                "package_priority": 1
            }
        ],
        "total_available": 1,
        "location": {
            "latitude": 40.7128,
            "longitude": -74.0060
        }
    }
}
```

---

## 💰 Earnings & Impression Tracking

### **Recording Impressions**
```http
POST /api/driver/ads/1/impression
Authorization: Bearer {token}
Content-Type: application/json

{
    "type": "display"  // or "qr_scan"
}
```

### **Impression Types & Rates**
- **`display`**: Ad shown on screen (typically $0.50)
- **`qr_scan`**: User scanned QR code (typically $2.00)

### **Earnings Summary**
```http
GET /api/driver/earnings/summary
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Earnings summary retrieved",
    "data": {
        "earnings": {
            "total_earnings": "125.50",
            "unpaid_amount": "45.75",
            "today": "12.00",
            "this_week": "67.25",
            "this_month": "125.50"
        },
        "statistics": {
            "total_impressions": 89,
            "qr_scans": 15
        }
    }
}
```

---

## 📱 QR Code Tracking System

### **How It Works**
1. Driver displays ad with QR code from API response
2. User scans QR code → Opens tracking URL
3. System automatically records QR scan impression
4. Driver earns higher rate for QR scan
5. User gets redirected to original destination

### **QR Tracking URL Format**
```
http://127.0.0.1:8000/api/qr/{base64_encoded_data}
```

**QR Data Structure:**
```
{ad_id}|{original_url}
```

**Example:**
- Original URL: `https://restaurant.example.com/menu`
- Ad ID: `1`
- Combined: `1|https://restaurant.example.com/menu`
- Base64 Encoded: `MXxodHRwczovL3Jlc3RhdXJhbnQuZXhhbXBsZS5jb20vbWVudQ==`
- Final QR URL: `http://127.0.0.1:8000/api/qr/MXxodHRwczovL3Jlc3RhdXJhbnQuZXhhbXBsZS5jb20vbWVudQ==`

---

## 🛠️ Flutter Integration Guide

### **1. HTTP Client Setup**
```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiService {
    static const String baseUrl = 'http://127.0.0.1:8000/api';
    static String? authToken;
    
    static Map<String, String> get headers => {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        if (authToken != null) 'Authorization': 'Bearer $authToken',
    };
}
```

### **2. Driver Login**
```dart
Future<Map<String, dynamic>> loginDriver(String email, String password) async {
    final response = await http.post(
        Uri.parse('$baseUrl/driver/login'),
        headers: headers,
        body: jsonEncode({
            'email': email,
            'password': password,
        }),
    );
    
    final data = jsonDecode(response.body);
    if (data['success']) {
        authToken = data['data']['token'];
    }
    return data;
}
```

### **3. Request Location-Based Ads**
```dart
Future<List<AdModel>> requestAds(double latitude, double longitude) async {
    final response = await http.post(
        Uri.parse('$baseUrl/driver/ads/request'),
        headers: headers,
        body: jsonEncode({
            'latitude': latitude,
            'longitude': longitude,
        }),
    );
    
    final data = jsonDecode(response.body);
    if (data['success']) {
        return data['data']['ads'].map<AdModel>((ad) => AdModel.fromJson(ad)).toList();
    }
    return [];
}
```

### **4. Record Impression**
```dart
Future<void> recordImpression(int adId, String type) async {
    await http.post(
        Uri.parse('$baseUrl/driver/ads/$adId/impression'),
        headers: headers,
        body: jsonEncode({'type': type}),
    );
}
```

### **5. Ad Display Widget**
```dart
class AdDisplayWidget extends StatelessWidget {
    final AdModel ad;
    
    @override
    Widget build(BuildContext context) {
        return Stack(
            children: [
                // Fullscreen ad image/video
                Container(
                    width: double.infinity,
                    height: double.infinity,
                    child: Image.network(ad.media.url),
                ),
                // QR Code positioned top-right
                Positioned(
                    top: 50,
                    right: 20,
                    child: Container(
                        padding: EdgeInsets.all(8),
                        decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(8),
                        ),
                        child: QrImage(
                            data: ad.qrCode.redirectUrl,
                            size: 100,
                        ),
                    ),
                ),
            ],
        );
    }
}
```

---

## 📊 Test Credentials & Data

### **Driver Account**
- **Email**: `testdriver@example.com`
- **Password**: `password123`

### **Test Location (NYC)**
- **Latitude**: `40.7128`
- **Longitude**: `--74.0060`

### **Sample Ad Location (Times Square)**
- **Latitude**: `40.7580`
- **Longitude**: `-73.9855`
- **Radius**: `5.0 miles`

---

## ⚡ Error Handling

### **Standard Error Response Format**
```json
{
    "success": false,
    "message": "Error description",
    "data": {
        "field_name": ["validation error messages"]
    }
}
```

### **Common HTTP Status Codes**
- **200**: Success
- **401**: Unauthorized (invalid/missing token)
- **422**: Validation Error
- **403**: Forbidden (account deactivated)
- **404**: Resource not found
- **410**: Resource gone (ad budget exceeded)

---

## 🔧 Development Notes

### **Laravel Server Commands**
```bash
# Start development server
php artisan serve

# Clear all caches
php artisan optimize:clear

# Check routes
php artisan route:list --path=api/driver
```

### **Database Seeding**
The API includes pre-seeded test data:
- Test driver account
- Sample ads with location data
- Package configurations with pricing

### **Environment Configuration**
- Ensure `api` routes are enabled in `bootstrap/app.php`
- Laravel Sanctum configured for API authentication
- Database migrations completed

---

## 🚀 Ready for Production

This API is **production-ready** with:
- ✅ Proper authentication & authorization
- ✅ Input validation & sanitization  
- ✅ Error handling & logging
- ✅ Geographic distance calculations
- ✅ Anti-spam algorithms
- ✅ Earnings tracking & management
- ✅ QR code tracking system
- ✅ RESTful design principles

**Start building your Flutter driver app now!** 📱💰

---

## 📞 Support

For any questions or issues with the API integration, please refer to:
1. This documentation
2. Postman collection examples  
3. Laravel documentation for advanced features

**Happy coding!** 🎉