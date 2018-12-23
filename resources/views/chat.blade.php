<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        * {
            margin: 0px;
            padding: 0px;
        }
    </style>
</head>
<body>
    <div style="margin-left:400px">
        <div style="border:1px solid;width: 600px;height: 500px;">
            <div id="msgArea" style="width:100%;height: 100%;text-align:start;resize: none;font-family: 微软雅黑;font-size: 20px;overflow-y: scroll"></div>
        </div>
        <div style="border:1px solid;width: 600px;height: 200px;">
            <div style="width:100%;height: 100%;">
                <textarea id="userMsg" style="width:100%;height: 100%;text-align:start;resize: none;font-family: 微软雅黑;font-size: 20px;"></textarea>
            </div>
        </div>
        <div style="border:1px solid;width: 600px;height: 25px;">
            <button style="float: right;" onclick="sendMsg()">send</button>
        </div>
    </div>
</body>

</html>
<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
<script>
    var ws;
    $(function () {
        link();
    })

    function link() {
        ws = new WebSocket("ws://192.168.75.101:9502");//连接服务器
        ws.onopen = function (event) {
            console.log(event);
            //alert(event);
        };
        ws.onmessage = function (event) {
            console.log(event);
            var msg = "<p>" + event.data + "</p>";
            $("#msgArea").append(msg);
        }
        ws.onclose = function (event) { alert("server out now status：" + this.readyState); };

        ws.onerror = function (event) { alert("WebSocket error！"); };
    }

    function sendMsg() {
        var msg = $("#userMsg").val();
        ws.send(msg);
    }
</script>