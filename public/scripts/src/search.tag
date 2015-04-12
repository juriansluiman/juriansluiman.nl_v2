<search>
  <search-form query={ opts.query } url={ opts.url } />
  <search-waiter if={ opts.query && !results } />
  <search-list results={ results } if={ results } />

  this.results = {};

  var query = opts.query, riot = this;

  if (query) {
    var url     = 'https://www.googleapis.com/customsearch/v1?fields=items(title,link)&prettyPrint=false&key=' + opts.config.key + '&cx=' + opts.config.cx + '&q=' + query;
        request = new XMLHttpRequest();

    request.open('GET', url, true);
    request.onload = function() {
      if (request.status >= 200 && request.status < 400) {
        var data = JSON.parse(request.response);
        riot.results = data.items;
        riot.update();
      }
    };
    request.send();
  }

</search>

<search-form>
  <form method="get" action="{ opts.url }">
      <input type="text" name="q" placeholder="What are you looking for?" value="{ opts.query }" class="search-query">
  </form>
</search-form>

<search-waiter>
  <div class="search-waiter"><span class="fa fa-spinner fa-5x fa-pulse"></span></div>
</search-waiter>

<search-list>
  <ul class="search-results">
    <search-result each={ opts.results } title={ this.title.split('·')[0].trim() } link={ this.link } if={ this.link !== 'https://juriansluiman.nl/'} />
  </ul>
</search-list>

<search-result>
  <li>
    <a href="{ opts.link }">
      { opts.title } <br><span class="search-link">{ opts.link }</span>
    </a>
  </li>
</search-result>