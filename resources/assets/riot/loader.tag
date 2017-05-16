<loader>

	<form onsubmit={ retrieve }>

		<div>
			<label for="paragraphs">Paragraphs</label>
			<div class="input-wrap">
				<input type="number" min="1" max="99" value="4" name="paragraphs" id="paragraphs" ref="paragraphs" />
			</div>
		</div>

		<div>
			<label for="words">Words per paragraph</label>
			<div class="input-wrap">
				<input type="number" min="1" max="500" value="50" name="words" id="words" ref="words" />
			</div>
		</div>

		<button type="submit"><span>Seize the memes of production</span></button>

	</form>

	<div ref="container" id="container"></div>

	loader = this;


	retrieve(e) {

		e.preventDefault();

		axios.post('/api', {
			paragraphs: loader.refs.paragraphs.value,
			words: loader.refs.words.value
		})
		.then(function (response) {
			loader.refs.container.innerHTML = response.data;
		})
		.catch(function (error) {
			console.log(error);
		});

	}

</loader>