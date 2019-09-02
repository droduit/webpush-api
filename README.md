# WebPush API

## How to use

```js
const API_URL = "https://dominique.leroduit.com/webpush/api.php";

var bodyObj = {
  subscriber: {
    endpoint: 'https://fcm.googleapis.com/fcm/send/f62gDWNV8No:APA91bHZ5A0tK9HgSEf7Yj_oGNa85lIKeedzucTkZ4dXY4_1GDQMnG1lEnK46aQviptKWjEZnlnPiyrjnatgsvCM3P3ZbXwzcEfVxewqQrLlBH8T1ZbHIcURinQHuyzGQqYe_uCDNM5c',
    authToken: "x2-Wy6YXaGlU5ABWd9YIzA",
    publicKey: "BJq90fq61XXV0tle4eL64g07xtmBscYN6fFHQbOk04anGNlwaQDVRTy7HgHuX_jnCEN7BRrkb-LjYqPffQ5Atvs",
  },
  notification: {
    title: "Titre de la notification",
    body: "Message de la notification"
  }
}
```

### JS
```js
fetch(API_URL, {
    method: 'POST',
    body: JSON.stringify(bodyObj)
}).then(function(response) {
    return response.json();
}).then(function(data) {
    console.log(data.status, data.message);
});
```

### JQuery
```js
$.post(API_URL, bodyObj, function(data) {
  console.log(data);
});
```
