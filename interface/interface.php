<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>whirl :: the google image blender</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/interface.js"></script>
	</head>
	<body>
		<div class="interface-container container">
			<header class="header">
				<h1>whirl</h1>
				<h2>the google image blender</h2>
			</header>
			<section class="chapter chapter-start">
				<h3>start</h3>
				<p>click on the button to start whirl</p>
				<button id="button-start" class="btn btn-default" type="button">start</button>
			</section>
			<section class="chapter chapter-cache">
				<h3>clear cache</h3>
				<p>delete all files from previous runs from the cache</p>
				<button id="button-cache" class="btn btn-default" type="button">clear</button>
				<div class="load-wrapper"><div class="load"></div></div>
				<div class="alert alert-success" role="alert">cache was cleared successfully</div>
				<div class="alert alert-danger" role="alert">there was a problem with the cache clearing</div>
			</section>
			<section class="chapter chapter-settings">
				<h3>settings</h3>
			</section>
			<section class="step step-term">
				<h4>term</h4>
				<p>Enter your search term for the images you want:</p>
				<form id="step-term" action="" method="post">
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">search term</span>
						<input type="text" name="term" class="form-control term" placeholder="term" aria-describedby="sizing-addon1">
						<span class="input-group-btn">
							<button id="button-term" class="btn btn-default" type="button">continue</button>
						</span>
					</div>
				</form>
				<div class="load-wrapper"><div class="load"></div></div>
				<div class="alert alert-success" role="alert">executed successfully</div>
				<div class="alert alert-danger" role="alert">please enter a search term</div>
			</section>
			<section class="step step-quantity">
				<h4>quantity</h4>
				<p>Enter your the number of how many images you want to blend (only natural numbers; 2 or above):</p>
				<form id="step-quantity" action="" method="post">
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">quantity</span>
						<input type="text" name="quantity" class="form-control quantity" value="40" aria-describedby="sizing-addon1">
						<span class="input-group-btn">
							<button id="button-quantity" class="btn btn-default" type="button">continue</button>
						</span>
					</div>
				</form>
				<div class="load-wrapper"><div class="load"></div></div>
				<div class="alert alert-success" role="alert">executed successfully</div>
				<div class="alert alert-danger" role="alert">please enter a valid natural number (2 or above)</div>
			</section>
			<section class="chapter chapter-execution">
				<h3>execution</h3>
			</section>
			<section class="step step-fetch">
				<h4>fetch results</h4>
				<p>fetch the results according to your search term</p>
				<form id="step-fetch" action="" method="post">
					<input type="hidden" name="fetch" value="1">
					<button id="button-fetch" class="btn btn-default" type="button">fetch results</button>
				</form>
				<div class="load-wrapper"><div class="load"></div></div>
				<div class="alert alert-success" role="alert">results fetched successfully</div>
			</section>
			<section class="step step-save">
				<h4>save results</h4>
				<p>save the results to the cache</p>
				<form id="step-save" action="" method="post">
					<input type="hidden" name="save" value="1">
					<button id="button-save" class="btn btn-default" type="button">save results</button>
				</form>
				<div class="load-wrapper"><div class="load"></div></div>
				<div class="alert alert-success" role="alert">results saved successfully</div>
			</section>
			<section class="step step-resize">
				<h4>resize results</h4>
				<p>resize/crop the images to a uniform format</p>
				<form id="step-resize" action="" method="post">
					<input type="hidden" name="resize" value="1">
					<button id="button-resize" class="btn btn-default" type="button">resize results</button>
				</form>
				<div class="load-wrapper"><div class="load"></div></div>
				<div class="alert alert-success" role="alert">results resized successfully</div>
			</section>
			<section class="step step-multiply">
				<h4>blend results</h4>
				<p>blend the images</p>
				<form id="step-multiply" action="" method="post">
					<input type="hidden" name="multiply" value="1">
					<button id="button-multiply" class="btn btn-default" type="button">multiply results</button>
				</form>
				<div class="load-wrapper"><div class="load"></div></div>
				<div class="alert alert-success" role="alert">results multiplied successfully</div>
			</section>
			<section class="step step-final">
				<h4>load result</h4>
				<p>loads the final result</p>
				<form id="step-final" action="" method="post">
					<input type="hidden" name="final" value="1">
					<button id="button-final" class="btn btn-default" type="button">load result</button>
				</form>
				<div class="load-wrapper"><div class="load"></div></div>
				<div class="alert alert-success" role="alert">final image loaded successfully</div>
			</section>
			<section class="step step-download">
				<h4>the final image</h4>
				<p>heres the final image. click on the image to save it</p>
				<a id="button-download" class="image"><img src=""></a>
			</section>
			<section class="step step-thanks">
				<h4>thanks for using whirl!</h4>
				<p class="author">
					<span>&copy; Stefan Gruber &lt;<a href="mailto:admin@blacksunshine.cc">admin@blacksunshine.cc</a>&gt;</span>
				</p>
				<p class="links">
					<a href="https://github.com/blacksunshineCoding/whirl">project<span>@</span>github</a>
					<a href="https://github.com/blacksunshineCoding">author<span>@</span>github</a>
				</p>
			</section>
			<section class="continue">
				<h3>follow the instruction and enter the form above to continue...</h3>
			</section>
			<div class="json-container"></div>
			<div class="hiddenInputs">
				<input type="hidden" class="hiddenTerm">
				<input type="hidden" class="hiddenQuantity">
			</div>
		</div>
	</body>
</html>