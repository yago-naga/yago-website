<footer>
    <div class="affiliation-logos">
        <a href="https://www.mpi-inf.mpg.de/"><img src="<?php site_url(); ?>/assets/images/logo-mpi.png" alt="Max Planck Institute for Informatics"/></a>
        <a href="https://www.telecom-paris.fr/"><img src="<?php site_url(); ?>/assets/images/logo-telecom-paris.png" alt="Télécom Paris"/></a>
    </div>
    <p>
        &copy; <?php echo date('Y'); ?> - <?php site_name(); ?>.
        This work is licensed under a <a rel="license" href="https://creativecommons.org/licenses/by/4.0/">Creative Commons Attribution 4.0 International License</a>.
        Hosted by <a href="https://www.telecom-paris.fr/">Télécom Paris</a>.
        Web design by <a href="https://alab-ux.com" target="_blank" rel="noopener noreferrer">ALAB UX</a>.
    </p>
</footer>

<script
    src="//tools-static.wmflabs.org/cdnjs/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="//tools-static.wmflabs.org/cdnjs/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@dagrejs/dagre@1.1.4/dist/dagre.min.js"></script>
<script type="text/javascript" src="<?php site_url(); ?>/js/scripts.js"></script>
<script type="text/javascript" src="<?php site_url(); ?>/js/property-modal.js"></script>
<script type="text/javascript" src="<?php site_url(); ?>/js/autocomplete.js"></script>
<?php if (isset($_GET['engine']) && $_GET['engine'] === 'blazegraph'): ?>
<script>
// Propagate ?engine=blazegraph across all internal links and forms
(function() {
    function addEngine(url) {
        try {
            var u = new URL(url, location.origin);
            if (u.origin === location.origin && !u.searchParams.has('engine')) {
                u.searchParams.set('engine', 'blazegraph');
                return u.toString();
            }
        } catch(e) {}
        return url;
    }
    document.addEventListener('click', function(e) {
        var a = e.target.closest('a[href]');
        if (a && a.href) a.href = addEngine(a.href);
    }, true);
    document.querySelectorAll('form').forEach(function(f) {
        if (!f.querySelector('input[name="engine"]')) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'engine'; inp.value = 'blazegraph';
            f.appendChild(inp);
        }
    });
})();
</script>
<?php endif; ?>
