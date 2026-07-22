<div align="center">
  <img src="https://images.squarespace-cdn.com/content/v1/607861b10c0e3b4816f56581/3eea3ca7-58ca-402d-9edf-b8e9e72eca3c/lightning.png?format=300w" alt="Photon Commerce">

  <h1>RECEIPT</h1>
  <p><strong>Extract structured data from receipts with 99%+ accuracy</strong></p>
  <p><strong>Powered by <a href="https://www.photoncommerce.com">Photon Commerce</a> — Managed AI Agents with data verification and validation</strong></p>

  <p>
    <a href="https://www.photoncommerce.com"><img src="https://img.shields.io/badge/SOC%202-Compliant-blue?style=for-the-badge" alt="SOC 2"></a>
    &nbsp;&nbsp;
    <a href="https://www.photoncommerce.com"><img src="https://img.shields.io/badge/GDPR-Attested-blue?style=for-the-badge" alt="GDPR"></a>
    &nbsp;&nbsp;
    <a href="https://www.photoncommerce.com/pricing"><img src="https://img.shields.io/badge/Accuracy-99%25%2B-00C853?style=for-the-badge" alt="Accuracy"></a>
    &nbsp;&nbsp;
    <a href="https://www.photoncommerce.com/platform"><img src="https://img.shields.io/badge/Languages-25%2B-FF6D00?style=for-the-badge" alt="Languages"></a>
  </p>

  [Website](https://www.photoncommerce.com) &nbsp;·&nbsp; [API Docs](https://apidocs.photoncommerce.com) &nbsp;·&nbsp; [Pricing](https://www.photoncommerce.com/pricing) &nbsp;·&nbsp; [Free Trial](https://app.photoncommerce.com) &nbsp;·&nbsp; [Try in ChatGPT](https://chatgpt.com/apps/photon-commerce/asdk_app_696685b735588191b9f25f976cfda7b2)
</div>

---

## Overview

This repository contains ready-to-run code samples for extracting structured data from receipts using the **Photon PRO API**.

Submit any receipt — PDF, image, Word, HTML, or email — and receive a structured JSON response with 100+ fields, verified to 99%+ accuracy by Photon's Managed Agents network of 2,300+ expert reviewers across 7+ countries.

---

## Extracted Fields

| Category | Fields |
|----------|--------|
| **Vendor** | Name, raw name, recipient, email, address, city, state, zip, country, phone, fax, website, VAT number, bank name, bank number, SWIFT, IBAN, account number |
| **Bill To** | Name, recipient, address, city, state, zip, VAT number, email |
| **Metadata** | Receipt number, check number, PO number, date, order date, category, currency, payment terms, account number, reference number, notes, pages, duplicate flag |
| **Amounts** | Balance due, total, subtotal, shipping, tax, tip, cashback, discount |
| **Line Items** | Description, quantity, unit, unit price, tax, tax rate, discount, amount |
| **Payment** | Payment type, card number, payment display name |

100+ standardised fields · 25+ document languages · Handwriting, stamps, barcodes, and tables supported

---

## Quick Start

```python
import requests

HEADERS = {
    "CLIENT-ID":     "YOUR_CLIENT_ID",
    "AUTHORIZATION": "apikey YOUR_USERNAME:YOUR_API_KEY",
    "PASSWORD":      "YOUR_PASSWORD",
    "SECRET-KEY":    "YOUR_SECRET_KEY",
}

# Step 1 — Submit
response = requests.post(
    "https://sandbox-api.photoncommerce.com/api/pro",
    headers=HEADERS,
    params={"doctype": "receipt"},
    files={"pdf": open("receipt.pdf", "rb")},
)
photon_key = response.json()["photon_key"]

# Step 2 — Retrieve
result = requests.get(
    "https://sandbox-api.photoncommerce.com/api/v4/json",
    headers=HEADERS,
    params={"photon_key": photon_key},
).json()["data"]

print(result["Vendor_Name"])   # Char Bar No. 7
print(result["Total"])         # 136.43
print(result["Payment_Type"])  # MasterCard
```

[Get your free sandbox credentials →](https://sandbox-api.photoncommerce.com/api/v4/register)

---

## Code Examples

| Language | File | Library |
|----------|------|---------|
| ![Python](https://img.shields.io/badge/Python-3776AB?style=flat-square&logo=python&logoColor=white) | [extract_receipt.py](extract_receipt.py) | requests |
| ![JavaScript](https://img.shields.io/badge/Node.js-339933?style=flat-square&logo=nodedotjs&logoColor=white) | [extract_receipt.js](extract_receipt.js) | fetch + form-data |

Every example supports both **local file upload** and **URL-based submission**, plus optional webhook callbacks.

---

## How It Works

```
Receipt In (PDF / image / Word / HTML / email)
    ↓
Photon Engine  —  AI + OCR + NLP
    ↓
Managed Agents QA  —  2,300+ expert reviewers
    ↓
Structured JSON Out  —  100+ verified fields
```

### Submission

```
POST /api/pro?doctype=receipt
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `doctype` | string | `receipt` |
| `url` | string | URL of a publicly accessible document (alternative to file upload) |
| `webhook_url` | string | Receive a callback when extraction is complete |
| `auth_token` | string | Token to verify the webhook callback |
| `ID` | string | Your own reference ID for this submission |
| `subaccount` | string | Route to a subaccount |
| `page_start` | integer | First page to process (multi-page PDFs) |
| `page_end` | integer | Last page to process (multi-page PDFs) |

### Retrieval

```
GET /api/v4/json?photon_key=YOUR_PHOTON_KEY
```

Or pass `webhook_url` at submission and Photon will POST the result to your endpoint when ready.

---

## Processing Times

| Account Type | Turnaround |
|-------------|------------|
| Trial | Up to 24 hours (Managed Agents included) |
| Production | seconds to 24 hours (Managed Agents included) |

> **Instant Managed Agent:** To activate, contact [support@photoncommerce.com](mailto:support@photoncommerce.com). Once activated, submit to `/api/v4` instead of `/api/pro` for near-instant results.

---

## Authentication

All requests require four headers:

```
CLIENT-ID:     your-client-id
AUTHORIZATION: apikey your-username:your-api-key
PASSWORD:      your-password
SECRET-KEY:    your-secret-key
```

Get credentials: [Register a free sandbox account](https://sandbox-api.photoncommerce.com/api/v4/register) (20 free calls, no credit card required) or sign up at [app.photoncommerce.com](https://app.photoncommerce.com).

---

## Sample Response

```json
{
  "data": {
    "Balance_Due": 136.43,
    "Total": 136.43,
    "Subtotal": 107.42,
    "Shipping": 0.0,
    "Tax": 9.67,
    "Tip": 19.34,
    "Cashback": 0.0,
    "Discount": 0.0,
    "Document_Type": "Receipt",
    "Invoice_Number": "4796",
    "Check_Number": "",
    "PO_Number": "",
    "Date": "2023-01-31",
    "Created": "",
    "Order_Date": "2023-01-31",
    "Due_Date": "",
    "Ship_Date": "",
    "Delivery_Date": "",
    "Service_Start_Date": "",
    "Service_End_Date": "",
    "Category": "Restaurant",
    "Currency_Code": "USD",
    "Payment_Terms": "",
    "Account_Number": "",
    "Bill_To_Name": "",
    "Bill_To_Recipient": "",
    "Bill_To_Address": "",
    "Bill_To_Address_Line": "",
    "Bill_To_City": "",
    "Bill_To_State": "",
    "Bill_To_Zipcode": "",
    "Bill_To_Vat_Number": "",
    "Bill_To_Email": "",
    "Card_Number": "XXXXXXXXXXXX1738",
    "Payment_Display_Name": "",
    "Payment_Type": "MasterCard",
    "Phone_Number": "",
    "Vat_Number": "",
    "Vendor_Name": "Char Bar No. 7",
    "Vendor_Raw_Name": "Char Bar No. 7",
    "Vendor_Recipient": "Bubba",
    "Vendor_Email": "",
    "Vendor_Address": "900 Jemison Lane Mountain Brook, AL 35223",
    "Vendor_Address_Line": "900 Jemison Lane",
    "Vendor_City": "Mountain Brook",
    "Vendor_State": "AL",
    "Vendor_Zipcode": "35223",
    "Vendor_Country": "US",
    "Vendor_Type": "",
    "Vendor_Phone": "205-802-9050",
    "Vendor_Fax": "",
    "Vendor_Website": "www.CharBar7.com",
    "Vendor_ABN_Number": "",
    "Vendor_Bank_Name": "",
    "Vendor_Bank_Number": "",
    "Vendor_Bank_Swift": "",
    "Vendor_IBAN": "",
    "Vendor_Account_Number": "",
    "Remit_To_Name": "",
    "Remit_To_Address": "",
    "All_Email_Addresses": "",
    "Ship_To_Name": "",
    "Ship_To_Address": "",
    "Carrier": "",
    "Tracking_Number": "",
    "Pages": 1,
    "Is_Duplicate": 0,
    "Notes": "",
    "Tax_Lines": [],
    "Line_Items": [
      { "Date": "2023-01-31", "Order": "", "Reference": "", "Description": "Smother Me Brg", "QTY": 1, "Unit": "", "Tax": 0.0, "Tax_Rate": 0.0, "Type": "", "Price": 15.50, "Discount": 0.0, "Amount": 15.50 },
      { "Date": "2023-01-31", "Order": "", "Reference": "", "Description": "Sprite", "QTY": 1, "Unit": "", "Tax": 0.0, "Tax_Rate": 0.0, "Type": "", "Price": 2.75,  "Discount": 0.0, "Amount": 2.75  },
      { "Date": "2023-01-31", "Order": "", "Reference": "", "Description": "STEAK Quesadil", "QTY": 1, "Unit": "", "Tax": 0.0, "Tax_Rate": 0.0, "Type": "", "Price": 14.25, "Discount": 0.0, "Amount": 14.25 },
      { "Date": "2023-01-31", "Order": "", "Reference": "", "Description": "Buffalo CHX Taco", "QTY": 1, "Unit": "", "Tax": 0.0, "Tax_Rate": 0.0, "Type": "", "Price": 11.79, "Discount": 0.0, "Amount": 11.79 },
      { "Date": "2023-01-31", "Order": "", "Reference": "", "Description": "Diet Coke", "QTY": 1, "Unit": "", "Tax": 0.0, "Tax_Rate": 0.0, "Type": "", "Price": 2.75,  "Discount": 0.0, "Amount": 2.75  },
      { "Date": "2023-01-31", "Order": "", "Reference": "", "Description": "DIY Blk Bean Brg $Cheddar", "QTY": 1, "Unit": "", "Tax": 0.0, "Tax_Rate": 0.0, "Type": "", "Price": 15.15, "Discount": 0.0, "Amount": 15.15 },
      { "Date": "2023-01-31", "Order": "", "Reference": "", "Description": "Leap Year Brg $Pepper Jack", "QTY": 1, "Unit": "", "Tax": 0.0, "Tax_Rate": 0.0, "Type": "", "Price": 2.75,  "Discount": 0.0, "Amount": 2.75  },
      { "Date": "2023-01-31", "Order": "", "Reference": "", "Description": "Grld Salmon Sld", "QTY": 1, "Unit": "", "Tax": 0.0, "Tax_Rate": 0.0, "Type": "", "Price": 16.25, "Discount": 0.0, "Amount": 16.25 },
      { "Date": "2023-01-31", "Order": "", "Reference": "", "Description": "Chip Chx Sand", "QTY": 1, "Unit": "", "Tax": 0.0, "Tax_Rate": 0.0, "Type": "", "Price": 13.99, "Discount": 0.0, "Amount": 13.99 }
    ],
    "Raw_Text": "Char Bar No. 7\n900 Jemison Lane\nMountain Brook, AL 35223\n205-802-9050\nwww.CharBar7.com\n\nServer: Bubba    Check #: 4796   01/31/2023\n\nSmother Me Brg           15.50\nSprite                    2.75\nSTEAK Quesadil           14.25\nBuffalo CHX Taco         11.79\nDiet Coke                 2.75\nDIY Blk Bean Brg         15.15\nLeap Year Brg             2.75\nGrld Salmon Sld          16.25\nChip Chx Sand            13.99\n\nSubtotal                107.42\nTax                       9.67\nTip                      19.34\nTotal                   136.43\n\nMasterCard XXXXXXXXXXXX1738\nThank you for dining with us!",
    "Reference_Number": "",
    "Fraud_Score": 5,
    "Risk_Score": 2,
    "Anomaly_Score": 5,
    "photon_key": "data/johndoe@abc.com/2026-01-05/23-40-12-067728_receipt.json",
    "doc_path": "data/johndoe@abc.com/2026-01-05/23-40-12-067728_receipt.pdf"
  },
  "message": "success",
  "status": "success"
}
```

---

## Other Document Types

| Repo | Document |
|------|----------|
| [`photon-invoice`](https://github.com/Photon-Commerce/photon-invoice) | Invoices |

---

## Links

| | |
|-|-|
| **Try in ChatGPT** | [Photon Commerce on ChatGPT](https://chatgpt.com/apps/photon-commerce/asdk_app_696685b735588191b9f25f976cfda7b2) |
| **API Docs** | [apidocs.photoncommerce.com](https://apidocs.photoncommerce.com) |
| **Free Trial** | [app.photoncommerce.com](https://app.photoncommerce.com) |
| **Pricing** | [photoncommerce.com/pricing](https://www.photoncommerce.com/pricing) |
| **Support** | [support@photoncommerce.com](mailto:support@photoncommerce.com) |
| **Enterprise** | [developers@photoncommerce.com](mailto:developers@photoncommerce.com) |
