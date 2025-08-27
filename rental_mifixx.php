
<style>
	#canvasContainer {
		overflow: hidden; /* Mengunci scroll halaman */
	}

	#signatureCanvas {
		width: 100%;
		height: 200px;
		border: 1px solid #000;
	}
</style>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const canvas = document.getElementById('signatureCanvas');
		const context = canvas.getContext('2d');
		let isDrawing = false;
		canvas.addEventListener('mousedown', () => {
			isDrawing = true;
			context.beginPath();
			console.log("ok");
		});

		canvas.addEventListener('mousemove', (event) => {
			if (!isDrawing) return;
			context.lineWidth = 2;
			context.lineCap = 'round';
			context.strokeStyle = '#000';
			context.lineTo(event.clientX - canvas.getBoundingClientRect().left, event.clientY - canvas.getBoundingClientRect().top);
			context.stroke();
			console.log("move");
		});

		canvas.addEventListener('mouseup', () => {
			isDrawing = false;
			updateSignatureData();
			console.log("sipp");
		});

		canvas.addEventListener('mouseout', () => {
			isDrawing = false;
		});

		const clearButton = document.getElementById('clearButton');
		clearButton.addEventListener('click', () => {
			context.clearRect(0, 0, canvas.width, canvas.height);
			updateSignatureData();
		});

		function updateSignatureData() {
			document.getElementById('signatureData').value = canvas.toDataURL();
		}
	});
</script>
				<div class='row' id="canvasContainer">
					<div class='col-12'>
						Tanda Tangan<br>
						<canvas id="signatureCanvas" height="200" style="border: 1px solid #000; width:400;"></canvas>
						<br>
						<input type="hidden" id="signatureData" name="signatureData"> 
					</div>
					<div class='col-3'>
						<button id="clearButton" type="button" class='btn btn-success btn-sm'>Clear</button>
					</div>
				</div>
				<button type='submit' class='btn-solid bg-dark' name='submit'>Submit</button>
			
