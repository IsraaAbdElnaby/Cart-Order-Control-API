# Voo CartOrderControl Module for Magento 2

## Overview
The CartOrderControl module provides custom cart and order management functionality for Magento 2. It includes features like daily order limits, custom cart operations, and enhanced order processing.

## Features
- Custom cart management API
- Daily order limit (2 orders per customer per day)
- Enhanced order submission process
- Cart validation and error handling
- Custom cart details mapping

## Installation

### Manual Installation
1. Create directory `app/code/Voo/CartOrderControl`
2. Copy module files to the directory
3. Enable the module:
```bash
php bin/magento module:enable Voo_CartOrderControl
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:clean
```

## API Endpoints
1. Get Cart Details ( Returns detailed information about the customer's cart)
```bash
GET /V1/voo-api/cart
```
2. Add/Update Cart Item (Add or update items in the cart)
```bash
POST /V1/voo-api/cart
```

3. Submit Order (Submit the current cart as an order)
```bash
POST /V1/order
```

