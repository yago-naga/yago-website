$(document).ready(function () {
    var modalEl = document.getElementById('property-values-modal');
    if (!modalEl) return;

    var apiUrl = modalEl.getAttribute('data-api-url');
    var currentState = {};

    function centerModal(modal) {
        var vw = window.innerWidth;
        var pct = vw <= 992 ? 0.80 : 0.55;
        var w = Math.min(vw * pct, 1000);
        modal.style.left = ((vw - w) / 2) + 'px';
        modal.style.right = 'auto';
    }

    var modalInstance = M.Modal.init(modalEl, {
        onOpenStart: function (modal) { centerModal(modal); },
        onOpenEnd: function (modal) { centerModal(modal); }
    });

    function buildPagination(current, total) {
        var VISIBLE = 7; // always show exactly this many page numbers
        var html = '';
        html += '<li class="pagination-prev ' + (current <= 1 ? 'disabled' : 'waves-effect') + '">';
        html += '<a href="#!"><i class="material-icons">chevron_left</i></a></li>';

        var pages = [];
        if (total <= VISIBLE) {
            for (var p = 1; p <= total; p++) pages.push(p);
        } else {
            var innerSize = VISIBLE - 2; // 5 pages between first and last
            var half = Math.floor(innerSize / 2);
            var start = Math.max(2, Math.min(current - half, total - innerSize));
            var end = start + innerSize - 1;

            pages.push(1);
            if (start > 2) pages.push('…');
            for (var p = start; p <= end; p++) pages.push(p);
            if (end < total - 1) pages.push('…');
            pages.push(total);
        }

        pages.forEach(function (p) {
            if (p === '…') {
                html += '<li class="disabled"><span>&hellip;</span></li>';
            } else {
                html += '<li class="pagination-page ' + (p === current ? 'active' : 'waves-effect') + '" data-page="' + p + '">';
                html += '<a href="#!">' + p + '</a></li>';
            }
        });

        html += '<li class="pagination-next ' + (current >= total ? 'disabled' : 'waves-effect') + '">';
        html += '<a href="#!"><i class="material-icons">chevron_right</i></a></li>';
        return html;
    }

    function fetchPage(page) {
        var $list = $('#modal-values-list');
        var $pagination = $('#modal-pagination');

        // Show spinner in list area but keep pagination visible
        $list.html('<li class="center-align" style="padding:2rem 0;">' +
            '<div class="preloader-wrapper small active">' +
            '<div class="spinner-layer spinner-blue-only">' +
            '<div class="circle-clipper left"><div class="circle"></div></div>' +
            '<div class="gap-patch"><div class="circle"></div></div>' +
            '<div class="circle-clipper right"><div class="circle"></div></div>' +
            '</div></div></li>');

        // Update pagination immediately to reflect the target page
        if (currentState.totalPages > 1) {
            $pagination.html(buildPagination(page, currentState.totalPages)).show();
        }

        $.getJSON(apiUrl, {
            resource: currentState.resource,
            property: currentState.property,
            reverse: currentState.reverse,
            page: page,
            lang: new URLSearchParams(window.location.search).get('lang') || 'en'
        }).done(function (data) {
            $('#modal-property-title').html(
                data.propertyLabel + ' <small>(' + data.total + ' values)</small>'
            );

            $list.empty();
            if (data.values.length === 0) {
                $list.html('<li>No values found.</li>');
            } else {
                data.values.forEach(function (html) {
                    $list.append('<li>' + html + '</li>');
                });
            }

            currentState.page = data.page;
            currentState.totalPages = data.totalPages;

            if (data.totalPages > 1) {
                $pagination.html(buildPagination(data.page, data.totalPages)).show();
            }
        }).fail(function () {
            $list.html('<li>Error loading values.</li>');
        });
    }

    $(document).on('click', '.more-values-link', function (e) {
        e.preventDefault();
        currentState = {
            resource: $(this).data('resource'),
            property: $(this).data('property'),
            reverse: $(this).data('reverse')
        };
        $('#modal-property-title').text('Loading\u2026');
        $('#modal-values-list').empty();
        $('#modal-pagination').empty().hide();
        modalInstance.open();
        fetchPage(1);
    });

    $('#property-values-modal').on('click', '.pagination-page a', function (e) {
        e.preventDefault();
        fetchPage($(this).parent().data('page'));
    });

    $('#property-values-modal').on('click', '.pagination-prev a', function (e) {
        e.preventDefault();
        if (currentState.page > 1) fetchPage(currentState.page - 1);
    });

    $('#property-values-modal').on('click', '.pagination-next a', function (e) {
        e.preventDefault();
        if (currentState.page < currentState.totalPages) fetchPage(currentState.page + 1);
    });
});
