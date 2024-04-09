    <form id="my-search" class="row">
        <div class="col s5 input-field">
            <input name="search" id="my-search-text" type="text">
            <label for="my-search-text">Search (use correct uppercase for named entities)</label>
        </div>
        <div class="col s3" style="margin-top: 1.5rem;">
            <button onclick="location.assign('/search/?search=' + encodeURIComponent(document.getElementById('my-search-text').value))" class="waves-effect waves-light btn">Search</button>
        </div>
    </form>

<?php 

require_once 'includes/sparql.php';

if (!isset($_GET['search']) || !$_GET['search']) {
    // do nothing
} else {

$entityname = $_GET['search'];

print "Entities called " . $entityname . ":\n<ul>\n";

$sparql = doSparqlQuery('SELECT DISTINCT ?s WHERE { ?s rdfs:label "' . $entityname . '"@en } LIMIT 100');
    foreach ($sparql['results']['bindings'] as $binding) {
        $entity = $binding['s']['value'];
        print "<li><a href='" . $entity ."'>" . uriToPrefixedName($entity) ."</a>\n";
    }

print "</ul>";
}
?>