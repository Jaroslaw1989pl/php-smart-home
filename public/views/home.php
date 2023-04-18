<!-- css files -->
<link rel="stylesheet" type="text/css" href="/statics/styles/home.css">

<!-- script files-->
<script type="module" src="/statics/scripts/home.js" defer></script>

<!-- page content -->
<h1>Home page</h1>

<button id="socket-btn">START</button>
<button id="stop-btn">STOP</button>

<?php
// $image = file_get_contents('/home/jaroslaw/Pictures/Screenshot from 2023-01-24 11-13-35.png');
// $image_codes = base64_encode($image);
?>
<!-- <image src="data:image/jpg;charset=utf-8;base64,<?php echo $image_codes; ?>" /> -->


<script>
    const stopBtn = document.getElementById("stop-btn");
    const startBtn = document.getElementById("socket-btn");

    startBtn.addEventListener("click", () => {
        const xhr = new XMLHttpRequest();
        xhr.onload = () => console.log(xhr.responseText);
        xhr.open("POST", "/socket/start");
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send();
    });

    stopBtn.addEventListener("click", () => {
        const xhr = new XMLHttpRequest();
        xhr.onload = () => console.log(typeof xhr.responseText, xhr.responseText);
        xhr.open("POST", "/socket/stop");
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send();
    });
</script>

<!-- <form action="/socket/start" method="post">
    <input type="submit" name="uuid" value="Start server">
</form> -->

