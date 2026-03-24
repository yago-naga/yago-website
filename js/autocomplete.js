$(document).ready(function () {
    var $input = $('#my-search-text');
    if (!$input.length) return;

    var apiUrl = $input.data('autocomplete-url');
    var debounceTimer = null;
    var selectedIndex = -1;
    var currentResults = [];
    var currentQuery = '';

    var $dropdown = $('<ul class="autocomplete-dropdown"></ul>');
    $input.closest('.input-field').append($dropdown);

    $input.on('input', function () {
        var query = $input.val().trim();
        clearTimeout(debounceTimer);
        selectedIndex = -1;

        if (query.length < 3) {
            hideDropdown();
            return;
        }

        debounceTimer = setTimeout(function () {
            fetchSuggestions(query);
        }, 400);
    });

    function fetchSuggestions(query) {
        currentQuery = query;
        var params = { q: query };

        var urlParams = new URLSearchParams(window.location.search);
        var lang = urlParams.get('lang');
        if (lang) params.lang = lang;
        var engine = urlParams.get('engine');
        if (engine) params.engine = engine;

        $.getJSON(apiUrl, params)
            .done(function (data) {
                if (query !== currentQuery) return;
                currentResults = data.results || [];
                renderDropdown(currentResults, query);
            })
            .fail(function () {
                hideDropdown();
            });
    }

    function renderDropdown(results, query) {
        $dropdown.empty();
        selectedIndex = -1;

        if (results.length === 0) {
            $dropdown.append(
                '<li class="autocomplete-no-results">No matches found</li>'
            );
            $dropdown.show();
            return;
        }

        results.forEach(function (item, index) {
            var matchLen = query.length;
            var highlighted = '<strong>' +
                escapeHtml(item.label.substring(0, matchLen)) +
                '</strong>' +
                escapeHtml(item.label.substring(matchLen));

            var $li = $('<li class="autocomplete-item"></li>').attr('data-index', index);
            $li[0].title = item.uri;
            $li.html(
                '<span class="autocomplete-label">' + highlighted + '</span>' +
                '<span class="autocomplete-uri">' + escapeHtml(item.prefixedName) + '</span>'
            );
            $li.on('mousedown', function (e) {
                e.preventDefault();
                navigateTo(item.url);
            });
            $dropdown.append($li);
        });

        $dropdown.show();
    }

    $input.on('keydown', function (e) {
        if (!$dropdown.is(':visible') || currentResults.length === 0) return;

        var items = $dropdown.find('.autocomplete-item');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
            updateSelection(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = Math.max(selectedIndex - 1, -1);
            updateSelection(items);
        } else if (e.key === 'Enter' && selectedIndex >= 0) {
            e.preventDefault();
            navigateTo(currentResults[selectedIndex].url);
        } else if (e.key === 'Escape') {
            hideDropdown();
            $input.blur();
        }
    });

    function updateSelection(items) {
        items.removeClass('active');
        if (selectedIndex >= 0) {
            $(items[selectedIndex]).addClass('active');
        }
    }

    $input.on('blur', function () {
        setTimeout(function () { hideDropdown(); }, 200);
    });

    $input.on('focus', function () {
        if (currentResults.length > 0 && $input.val().trim().length >= 3) {
            $dropdown.show();
        }
    });

    function hideDropdown() {
        $dropdown.hide().empty();
        currentResults = [];
        selectedIndex = -1;
    }

    function navigateTo(url) {
        window.location.href = url;
    }

    function escapeHtml(str) {
        return str.replace(/&/g, '&amp;')
                  .replace(/</g, '&lt;')
                  .replace(/>/g, '&gt;')
                  .replace(/"/g, '&quot;');
    }
});
