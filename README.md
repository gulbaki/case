

#  Symfony Discount and Order Management  

This is a **Symfony  REST API** for managing **discount rules** using the **Chain of Responsibility** pattern.  
It is containerized with **Docker** and uses **PostgreSQL** as the database.

---

## 🚀 Features
- **Order Management API** (Add, Delete, List Orders)
- **Discount Calculation API** (Applies multiple discount rules)
- **Factory Pattern for Creating Discount Rules**
- **Chain of Responsibility Pattern** for modular rule management
- **PostgreSQL 14** as the database
- **Docker Compose** for containerized development
- **Nginx** as a reverse proxy

---

## 📦 Installation & Setup

### **1️⃣ Clone the Repository**
```bash
git clone https://github.com/gulbaki/case
cd case
```

### **2️⃣ Start Docker Services**
Navigate to the `docker/` directory and build the containers:
```bash
docker compose up --build -d
```

### **3️⃣ Run Database Migrations**
```bash
docker exec -it your_container_id bash
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

---

# **📡 API Endpoints**
## **📌 Orders API**
### **1️⃣ Create a New Order**
#### **Endpoint:**
```http
POST /orders
```
#### **Request Payload:**
```json
{
  "customerId": 1,
  "items": [
    {
      "productId": 102,
      "quantity": 10,
      "unitPrice": "11.28"
    }
  ]
}
```
#### **Response:**
```json
{
  "id": 1,
  "customerId": 1,
  "items": [
    {
      "productId": 102,
      "quantity": 10,
      "unitPrice": "11.28",
      "total": "112.80"
    }
  ],
  "total": "112.80"
}
```

---

### **2️⃣ List All Orders**
#### **Endpoint:**
```http
GET /orders
```
#### **Response:**
```json
[
  {
    "id": 1,
    "customerId": 1,
    "items": [
      {
        "productId": 102,
        "quantity": 10,
        "unitPrice": "11.28",
        "total": "112.80"
      }
    ],
    "total": "112.80"
  }
]
```

---

### **3️⃣ Delete an Order**
#### **Endpoint:**
```http
DELETE /orders/{orderId}
```
#### **Response:**
```json
{
  "message": "Order deleted successfully"
}
```

---

## **📌 Discounts API**
### **1️⃣ Calculate Discounts for an Order**
#### **Endpoint:**
```http
POST /discounts/calculate
```
#### **Request Payload:**
```json
{
  "orderId": 3
}
```
#### **Response:**
```json
{
  "orderId": 3,
  "discounts": [
    {
      "discountReason": "BUY_6_GET_1",
      "discountAmount": "11.28",
      "subtotal": "1263.90"
    },
    {
      "discountReason": "10_PERCENT_OVER_1000",
      "discountAmount": "127.51",
      "subtotal": "1136.39"
    }
  ],
  "totalDiscount": "138.79",
  "discountedTotal": "1136.39"
}
```


#### ***SOURCES:***
[https://dotnettutorials.net/lesson/real-time-examples-of-chain-of-responsibility-design-pattern/](Sources)

[https://refactoring.guru/design-patterns/chain-of-responsibility/php/example](Sources)

[https://dotnettutorials.net/lesson/real-time-examples-of-chain-of-responsibility-design-pattern/](Sources)

[https://refactoring.guru/design-patterns/factory-method](Sources)



