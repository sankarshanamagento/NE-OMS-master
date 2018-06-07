### Organisation Creation API documentation

#### 1. Url:
    */NE-OMS/index.php/rest/V1/company/

#### 2. Method
    POST   

#### 3. Request Data
    {
    "companyname":"NetEnrich Inc",
    "companyemail":"Netenrich@netenrich.com",
    "useremail":"adminuser@netenrich.com",
    "firstname":"admin",
    "lastname":"user",
    "address":"226 Airport Parkway Suite # 550",
    "country":"US",
    "state":"CA",
    "city":"San Jose",
    "zip":"9511024",
    "website":"www.netenrich.com",
    "phone":"4084365900",
    "fax":"4084365901",
    "organisationtype":"1",
    "timezone":"America/Los_Angeles",
    "parent_id":"0",
    "status":"1",
    "divaid":"2016101819336",
    "usertype":"company"
  }
####  4.Output
    * Success:
       {"status":"success","resoponse":"Organisation modified successfully"}
    * Failure:
       {"status":"failed","resoponse":"Error message"}

### Organisation Updation API documentation

#### 1. Url:
    */NE-OMS/index.php/rest/V1/company/

#### 2. Method
    PUT  

#### 3. Request Data
    {
    "companyname":"NetEnrich Inc",
    "companyemail":"Netenrich@netenrich.com",
    "useremail":"adminuser@netenrich.com",
    "firstname":"admin",
    "lastname":"user",
    "address":"226 Airport Parkway Suite # 550",
    "country":"US",
    "state":"CA",
    "city":"San Jose",
    "zip":"9511024",
    "website":"www.netenrich.com",
    "phone":"4084365900",
    "fax":"4084365901",
    "organisationtype":"1",
    "timezone":"America/Los_Angeles",
    "parent_id":"0",
    "status":"1",
    "divaid":"2016101819336",
    "usertype":"company"
  }
####  4.Output
    * Success:
       {"status":"success","resoponse":"Organisation modified successfully"}
    * Failure:
       {"status":"failed","resoponse":"Error message"}

### Organisation deletion API

#### 1.Url
      */NE-OMS/index.php/rest/V1/company/{organisationId}

#### 2.Method
      DELETE

#### 3.Output
      * Success:
            {"status":"success","resoponse":"Organisation successfully deleted"}
      * Faliure:
            {"status":"success","resoponse":"Error message"}


### Order View Information API documentation

#### 1. Url
      */NE-OMS/index.php/rest/V1/orders/{orderId}
#### 2. Method
      GET
#### 3. Response JSON
        {
        "base_currency_code":"INR",
        "base_discount_amount":0,
        "base_grand_total":38,
        "base_discount_tax_compensation_amount":0,
        "base_discount_tax_compensation_invoiced":null,
        "base_discount_tax_compensation_refunded":null,
        "base_shipping_amount":10,
        "base_shipping_discount_amount":0,
        "base_shipping_discount_tax_compensation_amnt":null,
        "base_shipping_incl_tax":10,
        "base_shipping_tax_amount":0,
        "base_subtotal":28,
        "base_subtotal_incl_tax":28,
        "base_tax_amount":0,
        "base_total_due":38,
        "base_to_global_rate":1,
        "base_to_order_rate":1,
        "billing_address_id":6,
        "created_at":"2017-03-10 11:47:29",
        "customer_email":"pillar@gmail.com",
        "customer_firstname":"pillaR",
        "customer_group_id":7,
        "customer_id":10,
        "customer_is_guest":0,
        "customer_lastname":"pillaR",
        "customer_note_notify":1,
        "discount_amount":0,
        "email_sent":1,
        "entity_id":3,
        "global_currency_code":"INR",
        "grand_total":38,
        "discount_tax_compensation_amount":0,
        "discount_tax_compensation_invoiced":null,
        "discount_tax_compensation_refunded":null,
        "increment_id":"000000003",
        "is_virtual":0,
        "order_currency_code":"INR",
        "protect_code":"eff6e4",
        "quote_id":106,
        "shipping_amount":10,
        "shipping_description":"Flat Rate - Fixed",
        "shipping_discount_amount":0,
        "shipping_discount_tax_compensation_amount":0,
        "shipping_incl_tax":10,
        "shipping_tax_amount":0,
        "state":"new",
        "status":"pending",
        "store_currency_code":"INR",
        "store_id":1,
        "store_name":"Main Website\r\nMain Website Store",
        "store_to_base_rate":0,
        "store_to_order_rate":0,
        "subtotal":28,
        "subtotal_incl_tax":28,
        "tax_amount":0,
        "total_due":38,
        "total_item_count":2,
        "total_qty_ordered":2,
        "updated_at":"2017-03-10 11:47:39",
        "weight":2,
        "items":[
        {
          "amount_refunded":0,
          "base_amount_refunded":0,
          "base_discount_amount":0,
          "base_discount_invoiced":0,
          "base_discount_tax_compensation_amount":0,
          "base_discount_tax_compensation_invoiced":null,
          "base_discount_tax_compensation_refunded":null,
          "base_original_price":14,
          "base_price":14,
          "base_price_incl_tax":14,
          "base_row_invoiced":0,
          "base_row_total":14,
          "base_row_total_incl_tax":14,
          "base_tax_amount":0,
          "base_tax_invoiced":0,
          "created_at":"2017-03-10 11:47:29",
          "discount_amount":0,
          "discount_invoiced":0,
          "discount_percent":0,
          "free_shipping":0,
          "discount_tax_compensation_amount":0,
          "discount_tax_compensation_canceled":null,
          "discount_tax_compensation_invoiced":null,
          "discount_tax_compensation_refunded":null,
          "is_qty_decimal":0,
          "is_virtual":0,
          "item_id":3,
          "name":"Services5",
          "no_discount":0,
          "order_id":3,
          "original_price":14,
          "price":14,
          "price_incl_tax":14,
          "product_id":6,
          "product_type":"simple",
          "qty_canceled":0,
          "qty_invoiced":0,
          "qty_ordered":1,
          "qty_refunded":0,
          "qty_shipped":0,
          "quote_item_id":10,
          "row_invoiced":0,
          "row_total":14,
          "row_total_incl_tax":14,
          "row_weight":1,
          "sku":"24-WG089",
          "store_id":1,
          "tax_amount":0,
          "tax_invoiced":0,
          "tax_percent":0,
          "updated_at":"2017-03-10 11:47:29",
          "weight":1
        }],
        "billing_address":{
        "address_type":"billing",
        "city":"hyderabadh info",
        "country_id":"IN",
        "customer_address_id":16,
        "email":"pillar@gmail.com",
        "entity_id":6,
        "firstname":"info",
        "lastname":"info",
        "parent_id":3,
        "postcode":"533223",
        "region":"San jose info",
        "region_code":"San jose info",
        "street":[
          "plot#777-jublihill-info"
        ],
        "telephone":"9666132912"
        },
        "payment":{
        "account_status":null,
        "additional_information":[
          "Check \/ Money order",
          null,
          null
        ],
        "amount_ordered":38,
        "base_amount_ordered":38,
        "base_shipping_amount":10,
        "cc_exp_year":"0",
        "cc_last4":null,
        "cc_ss_start_month":"0",
        "cc_ss_start_year":"0",
        "entity_id":3,
        "method":"checkmo",
        "parent_id":3,
        "shipping_amount":10,
        "extension_attributes":[

        ]
        },
        "status_histories":[

        ],
        "extension_attributes":{
        "shipping_assignments":[
          {
            "shipping":{
              "address":{
                "address_type":"shipping",
                "city":"hyderabadh pillar",
                "country_id":"IN",
                "customer_address_id":15,
                "email":"pillar@gmail.com",
                "entity_id":5,
                "firstname":"pillaR",
                "lastname":"pillaR",
                "parent_id":3,
                "postcode":"533223",
                "region":"San jose pillar",
                "region_code":"San jose pillar",
                "street":[
                  "plot#777-jublihill-pillar"
                ],
                "telephone":"9666132912"
              },
              "method":"flatrate_flatrate",
              "total":{
                "base_shipping_amount":10,
                "base_shipping_discount_amount":0,
                "base_shipping_discount_tax_compensation_amnt":null,
                "base_shipping_incl_tax":10,
                "base_shipping_tax_amount":0,
                "shipping_amount":10,
                "shipping_discount_amount":0,
                "shipping_discount_tax_compensation_amount":0,
                "shipping_incl_tax":10,
                "shipping_tax_amount":0
              }
            },
            "items":[
              {
                "amount_refunded":0,
                "base_amount_refunded":0,
                "base_discount_amount":0,
                "base_discount_invoiced":0,
                "base_discount_tax_compensation_amount":0,
                "base_discount_tax_compensation_invoiced":null,
                "base_discount_tax_compensation_refunded":null,
                "base_original_price":14,
                "base_price":14,
                "base_price_incl_tax":14,
                "base_row_invoiced":0,
                "base_row_total":14,
                "base_row_total_incl_tax":14,
                "base_tax_amount":0,
                "base_tax_invoiced":0,
                "created_at":"2017-03-10 11:47:29",
                "discount_amount":0,
                "discount_invoiced":0,
                "discount_percent":0,
                "free_shipping":0,
                "discount_tax_compensation_amount":0,
                "discount_tax_compensation_canceled":null,
                "discount_tax_compensation_invoiced":null,
                "discount_tax_compensation_refunded":null,
                "is_qty_decimal":0,
                "is_virtual":0,
                "item_id":3,
                "name":"Services5",
                "no_discount":0,
                "order_id":3,
                "original_price":14,
                "price":14,
                "price_incl_tax":14,
                "product_id":6,
                "product_type":"simple",
                "qty_canceled":0,
                "qty_invoiced":0,
                "qty_ordered":1,
                "qty_refunded":0,
                "qty_shipped":0,
                "quote_item_id":10,
                "row_invoiced":0,
                "row_total":14,
                "row_total_incl_tax":14,
                "row_weight":1,
                "sku":"24-WG089",
                "store_id":1,
                "tax_amount":0,
                "tax_invoiced":0,
                "tax_percent":0,
                "updated_at":"2017-03-10 11:47:29",
                "weight":1
              },
              {
                "amount_refunded":0,
                "base_amount_refunded":0,
                "base_discount_amount":0,
                "base_discount_invoiced":0,
                "base_discount_tax_compensation_amount":0,
                "base_discount_tax_compensation_invoiced":null,
                "base_discount_tax_compensation_refunded":null,
                "base_original_price":14,
                "base_price":14,
                "base_price_incl_tax":14,
                "base_row_invoiced":0,
                "base_row_total":14,
                "base_row_total_incl_tax":14,
                "base_tax_amount":0,
                "base_tax_invoiced":0,
                "created_at":"2017-03-10 11:47:29",
                "discount_amount":0,
                "discount_invoiced":0,
                "discount_percent":0,
                "free_shipping":0,
                "discount_tax_compensation_amount":0,
                "discount_tax_compensation_canceled":null,
                "discount_tax_compensation_invoiced":null,
                "discount_tax_compensation_refunded":null,
                "is_qty_decimal":0,
                "is_virtual":0,
                "item_id":4,
                "name":"Services6",
                "no_discount":0,
                "order_id":3,
                "original_price":14,
                "price":14,
                "price_incl_tax":14,
                "product_id":7,
                "product_type":"simple",
                "qty_canceled":0,
                "qty_invoiced":0,
                "qty_ordered":1,
                "qty_refunded":0,
                "qty_shipped":0,
                "quote_item_id":11,
                "row_invoiced":0,
                "row_total":14,
                "row_total_incl_tax":14,
                "row_weight":1,
                "sku":"24-WG090",
                "store_id":1,
                "tax_amount":0,
                "tax_invoiced":0,
                "tax_percent":0,
                "updated_at":"2017-03-10 11:47:29",
                "weight":1
              }
            ]
          }
        ]
        }
        }

#### 4. Output
      * Success
        * Response : Response JSON
        * Status  : 200 OK
      * Failure
        * Response : Requested entity doesn't exist
        * Status : 404 NOT FOUND


### Partner orders list  API documentation

#### 1. Url:
    */NE-OMS/index.php/rest/V1/partnerorders/{id}/?currentpage={num}&pagesize={num} Query params are optional

#### 2. Method
    GET   

#### 3. Response JSON

     [{"count":10}{
      "orderid": "000000003",
      "purchasepoint": "Main Website\r\nMain Website Store",
      "Billto": "vara vara",
      "Paidto": "Sam Paul",
      "Status": "pending",
      "Basetotal": "792.0000",
      "purchasetotal": "792.0000",
      "PurchaseDate": "2017-04-17 13:09:47"
    },
    {
      "orderid": "000000004",
      "purchasepoint": "Main Website\r\nMain Website Store",
      "Billto": "vara vara",
      "Paidto": "Peter Reuters",
      "Status": "pending",
      "Basetotal": "921.0000",
      "purchasetotal": "921.0000",
      "PurchaseDate": "2017-04-17 13:15:10"
    },
    {
      "orderid": "000000005",
      "purchasepoint": "Main Website\r\nMain Website Store",
      "Billto": "vara vara",
      "Paidto": "client Aceme",
      "Status": "pending",
      "Basetotal": "420.0000",
      "purchasetotal": "420.0000",
      "PurchaseDate": "2017-04-17 13:21:24"
    },
    {
      "orderid": "000000006",
      "purchasepoint": "Main Website\r\nMain Website Store",
      "Billto": "vara vara",
      "Paidto": "peter Steve",
      "Status": "pending",
      "Basetotal": "742.2600",
      "purchasetotal": "742.2600",
      "PurchaseDate": "2017-04-17 13:25:54"
    },
    {
      "orderid": "000000007",
      "purchasepoint": "Main Website\r\nMain Website Store",
      "Billto": "vara vara",
      "Paidto": "Christopher Reaburn",
      "Status": "pending",
      "Basetotal": "1043.0000",
      "purchasetotal": "1043.0000",
      "PurchaseDate": "2017-04-17 13:29:57"
    }]

####  4.Output
    * Success:
      * Response : Success
      * Status : 200 OK
    * Failure:
      * Response : No Such Entity id data is there
      * Status: 400 BAD REQUEST


### Clients orders list  API documentation

#### 1. Url:
    */NE-OMS/index.php/rest/V1/clientorders/{id}/?currentpage={num}&pagesize={num} Query params are optional

#### 2. Method
    GET   

#### 3. Response JSON

    [{"count":10},{
    "orderid": "000000003",
    "purchasepoint": "Main Website\r\nMain Website Store",
    "Billto": "vara vara",
    "Paidto": "Sam Paul",
    "Status": "pending",
    "Basetotal": "792.0000",
    "purchasetotal": "792.0000",
    "PurchaseDate": "2017-04-17 13:09:47"
    },
    {
      "orderid": "000000004",
      "purchasepoint": "Main Website\r\nMain Website Store",
      "Billto": "vara vara",
      "Paidto": "Peter Reuters",
      "Status": "pending",
      "Basetotal": "921.0000",
      "purchasetotal": "921.0000",
      "PurchaseDate": "2017-04-17 13:15:10"
    },
    {
      "orderid": "000000005",
      "purchasepoint": "Main Website\r\nMain Website Store",
      "Billto": "vara vara",
      "Paidto": "client Aceme",
      "Status": "pending",
      "Basetotal": "420.0000",
      "purchasetotal": "420.0000",
      "PurchaseDate": "2017-04-17 13:21:24"
    },
    {
      "orderid": "000000006",
      "purchasepoint": "Main Website\r\nMain Website Store",
      "Billto": "vara vara",
      "Paidto": "peter Steve",
      "Status": "pending",
      "Basetotal": "742.2600",
      "purchasetotal": "742.2600",
      "PurchaseDate": "2017-04-17 13:25:54"
    },
    {
      "orderid": "000000007",
      "purchasepoint": "Main Website\r\nMain Website Store",
      "Billto": "vara vara",
      "Paidto": "Christopher Reaburn",
      "Status": "pending",
      "Basetotal": "1043.0000",
      "purchasetotal": "1043.0000",
      "PurchaseDate": "2017-04-17 13:29:57"
    }]


####  4.Output
    * Success:
      * Response : Success
      * Status : 200 OK
    * Failure:
      * Response : No Such Entity id data is there
      * Status: 400 BAD REQUEST
