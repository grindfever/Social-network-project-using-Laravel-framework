<section id="search-section">
    <article class="search-article">
        <div class="search-container">
            <form class="d-flex" action="/dashboard">
                @csrf
                <input class="form-control me-sm-2" type="text" name="query" placeholder="search">
                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
            </form>
            <button class="close-button" onclick="clearSearchResults()">x</button>
        </div>
        <div class="search-results-container">
            <div class="search-results"></div>
        </div>
    </article>
</section>
