/**
 * Resource page interactive cards: excluded facts (AJAX) and child classes (lazy tree).
 * Loaded on entity/class resource pages. Reads config from data attributes on card elements.
 */

/**
 * Set up a collapsible card toggle (expand/collapse icon + card body visibility).
 */
function initCollapseToggle(toggleId) {
    var toggle = document.getElementById(toggleId);
    if (!toggle) return;
    var cardContent = toggle.closest('.card-content');
    toggle.addEventListener('click', function() {
        cardContent.classList.toggle('collapsed');
        toggle.classList.toggle('collapsed');
        toggle.textContent = cardContent.classList.contains('collapsed') ? 'expand_more' : 'expand_less';
    });
}

/**
 * Initialize the excluded facts card: fetch facts via API, show card if data exists,
 * render table on first expand.
 *
 * @param {string} subject  Entity URI to look up
 * @param {string} apiUrl   Excluded facts API endpoint URL
 */
function initExcludedFacts(subject, apiUrl) {
    var card = document.getElementById('excluded-card');
    if (!card) return;

    var loaded = false;
    fetch(apiUrl + '?subject=' + encodeURIComponent(subject))
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.facts && data.facts.length > 0) {
                card.style.display = '';
                card._facts = data.facts;
            }
        });

    var toggle = document.getElementById('excluded-toggle');
    var cardContent = toggle.closest('.card-content');
    toggle.addEventListener('click', function() {
        var wasCollapsed = cardContent.classList.contains('collapsed');
        if (wasCollapsed && !loaded && card._facts) {
            var html = '<table><thead><tr><th>Predicate</th><th>Object</th><th>Reason</th></tr></thead><tbody>';
            card._facts.forEach(function(f) {
                var pred = '<a href="' + f.predicate_url + '">' + f.predicate_display + '</a>';
                var obj = f.object_url ? '<a href="' + f.object_url + '">' + f.object_display + '</a>' : f.object_display;
                html += '<tr><td>' + pred + '</td><td>' + obj + '</td><td style="color:#999;font-size:0.9em">' + f.reason + '</td></tr>';
            });
            html += '</tbody></table>';
            document.getElementById('excluded-table').innerHTML = html;
            loaded = true;
        }
        cardContent.classList.toggle('collapsed');
        toggle.classList.toggle('collapsed');
        toggle.textContent = cardContent.classList.contains('collapsed') ? 'expand_more' : 'expand_less';
    });
}

/**
 * Initialize the child classes card: lazily loads children via API, supports expand/collapse subtrees.
 *
 * @param {string} classUri  URI of the parent class
 * @param {string} lang      Language code for labels
 */
function initChildClasses(classUri, lang) {
    var card = document.getElementById('children-card');
    if (!card) return;

    function loadChildren(parentUri, ul, cb) {
        var li = document.createElement('li');
        li.className = 'tree-edge';
        li.textContent = 'Loading\u2026';
        li.style.color = '#999';
        ul.appendChild(li);

        fetch('/api/class_children.php?class=' + encodeURIComponent(parentUri) + '&lang=' + encodeURIComponent(lang))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                ul.removeChild(li);
                var items = data.children || [];
                items.forEach(function(c) {
                    var node = document.createElement('li');
                    node.className = 'tree-edge';
                    var a = document.createElement('a');
                    a.href = c.url;
                    a.textContent = c.prefixed;
                    if (c.label || c.comment) a.title = (c.label || '') + (c.comment ? ', ' + c.comment : '');
                    node.appendChild(a);

                    if (c.hasChildren) {
                        var btn = document.createElement('i');
                        btn.className = 'material-icons tiny';
                        btn.textContent = 'expand_more';
                        btn.style.cssText = 'cursor:pointer;vertical-align:middle;margin-left:4px;color:#999';
                        var sub = document.createElement('ul');
                        sub.className = 'tree-node';
                        sub.style.display = 'none';
                        var fetched = { v: false };
                        btn.addEventListener('click', (function(b, s, u, f) {
                            return function() {
                                var open = s.style.display !== 'none';
                                s.style.display = open ? 'none' : 'block';
                                b.textContent = open ? 'expand_more' : 'expand_less';
                                if (!f.v) { f.v = true; loadChildren(u, s); }
                            };
                        })(btn, sub, c.uri, fetched));
                        node.appendChild(btn);
                        node.appendChild(sub);
                    }
                    ul.appendChild(node);
                });
                if (cb) cb(items.length);
            });
    }

    var tree = document.getElementById('children-tree');
    loadChildren(classUri, tree, function(count) {
        if (count > 0) card.style.display = '';
    });

    initCollapseToggle('children-toggle');
}
