var socket;
$(function(){
    // create object
    try {
        socket = new YiiNodeSocket();

        // enable debug mode
        socket.debug(true);

        socket.onConnect(function () {
            console.log('onConnect');
        });

        socket.onDisconnect(function () {
            console.log('onDisconnect');
        });

        socket.onConnecting(function () {
            console.log('onConnecting');
        });

        socket.onReconnect(function () {
            console.log('onReconnect');
        });

        // add event listener
        socket.on('eventChange', function (data) {
            console.log(data);
        });

        // add event listener
        socket.on('updateBoard', function (data) {
            console.log(data);
        });

        // add event listener
        socket.on('eventRemove', function (data) {

        });

        socket.on('exCommand', function (data) {
            if(data.name !== undefined) {
                switch (data.name) {
                    case 'reload':
                        window.location.reload();
                        break;
                    case 'link':
                        window.location.href = data.link;
                        break;
                    default:

                        break;
                }
            }
        });
    } catch (ex) {
        console.log(ex);
    }
});