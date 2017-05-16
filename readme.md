# CommunIpsum

A simple, socialist lorem ipsum text generator built on Laravel Lumen. Visit [the site](https://communism.cool), or send a POST request to /api.

## API

The API accepts three options:

paragraphs `Integer (1-99)` `default: 4`

The number of paragraphs to retrieve.

words `Integer (1-500)` `default: 50`

The minimum number of words per paragraph. Sentence counts vary, so this is the amount of words after which a paragragh will get closed.

html `Boolean` `default: true`

Whether to wrap the paragraphs in `<p>` tags or not.
