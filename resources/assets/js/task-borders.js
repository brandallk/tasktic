
(function() {
    const canvasElements = document.querySelectorAll('canvas.task-border');

    function drawTaskBorders() {
        canvasElements.forEach(function(canvas) {
            if (canvas.getContext) {
                let ctx = canvas.getContext('2d');
                const taskDiv = canvas.parentElement;
                let width = taskDiv.clientWidth;
                let height = 5;
                ctx.canvas.width = width;
                ctx.canvas.height = height;
                ctx.fillStyle = '#697cae';
                
                ctx.beginPath();
                ctx.moveTo(0, height/2);
                ctx.quadraticCurveTo(width/2, 0, width, height/2);
                ctx.quadraticCurveTo(width/2, height, 0, height/2);
                ctx.fill();
            }
        });
    }

    window.onload = drawTaskBorders;
    window.onresize = drawTaskBorders;
})();
