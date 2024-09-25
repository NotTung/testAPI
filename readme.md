GET: GET http://yourserver/controller.php
POST: POST http://yourserver/controller.php với body JSON (Postman: Body > Raw) như:

{
"name": "Sản phẩm mới",
"price": 200
}

PUT: PUT http://yourserver/controller.php với body JSON như:

{
"id": 1,
"name": "Sản phẩm đã cập nhật",
"price": 250
}

DELETE: DELETE http://yourserver/controller.php với body JSON như:

    {
        "id": 1
    }
