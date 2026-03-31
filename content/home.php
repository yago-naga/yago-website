<script type="application/ld+json"><?php include(dirname(__FILE__) . '/dataset.json'); ?></script>
<h1>YAGO: A High-Quality Knowledge Base</h1>

<p>
    YAGO is a large knowledge base with general knowledge about people, cities, countries, movies, and organizations.
</p>

<?php
require_once 'includes/sparql.php';
$demoResource = 'http://yago-knowledge.org/resource/Elvis_Presley';
$demoLabel = 'Elvis Presley';
$demoLang = Locale::getPrimaryLanguage($GLOBALS['locale']);
$demoTaxonomy = getTaxonomyEdges($demoResource, $demoLang, false);
$demoTypes = $demoTaxonomy['types'];
if (!empty($demoTaxonomy['edges'])):
?>
<a href="<?php site_url(); ?>/resource/Elvis_Presley" id="home-dag" style="display: block; text-align: center;"></a>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof dagre === 'undefined') return;

    var taxonomyNodes = <?php echo json_encode($demoTaxonomy['nodes']); ?>;
    var taxonomyEdges = <?php echo json_encode($demoTaxonomy['edges']); ?>;
    var entityUri = <?php echo json_encode($demoResource); ?>;
    var entityLabel = <?php echo json_encode($demoLabel); ?>;
    var directTypes = <?php echo json_encode($demoTypes); ?>;

    var g = new dagre.graphlib.Graph();
    g.setGraph({ rankdir: 'BT', nodesep: 20, ranksep: 40, marginx: 15, marginy: 15 });
    g.setDefaultEdgeLabel(function() { return {}; });

    var tmpSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    tmpSvg.style.position = 'absolute';
    tmpSvg.style.visibility = 'hidden';
    document.body.appendChild(tmpSvg);
    var tmpText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    tmpText.setAttribute('font-family', 'Open Sans, sans-serif');
    tmpText.setAttribute('font-size', '12');
    tmpSvg.appendChild(tmpText);

    function measureText(label) {
        tmpText.textContent = label;
        return tmpText.getBBox().width;
    }

    for (var uri in taxonomyNodes) {
        var node = taxonomyNodes[uri];
        var w = measureText(node.label) + 30;
        g.setNode(uri, { label: node.label, width: Math.max(w, 50), height: 30, url: node.url, isSchema: node.isSchema });
    }

    var ew = measureText(entityLabel) + 30;
    g.setNode(entityUri, { label: entityLabel, width: Math.max(ew, 50), height: 30, url: '/resource/Elvis_Presley', isEntity: true });
    for (var i = 0; i < directTypes.length; i++) {
        if (g.hasNode(directTypes[i])) {
            g.setEdge(entityUri, directTypes[i]);
        }
    }

    for (var i = 0; i < taxonomyEdges.length; i++) {
        g.setEdge(taxonomyEdges[i].child, taxonomyEdges[i].parent);
    }

    document.body.removeChild(tmpSvg);
    dagre.layout(g);

    var graph = g.graph();
    var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + graph.width + '" height="' + graph.height + '">';
    svg += '<defs><marker id="home-dag-arrow" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto">';
    svg += '<path d="M 0 0 L 10 5 L 0 10 z" fill="#9e9e9e" opacity="0.5"/></marker></defs>';

    g.edges().forEach(function(e) {
        var edge = g.edge(e);
        var points = edge.points;
        if (points && points.length >= 2) {
            var d = 'M ' + points[0].x + ' ' + points[0].y;
            for (var j = 1; j < points.length; j++) {
                d += ' L ' + points[j].x + ' ' + points[j].y;
            }
            svg += '<path d="' + d + '" fill="none" stroke="#9e9e9e" stroke-width="1.2" marker-end="url(#home-dag-arrow)"/>';
        }
    });

    g.nodes().forEach(function(v) {
        var node = g.node(v);
        var x = node.x - node.width / 2;
        var y = node.y - node.height / 2;
        var fill, stroke, textFill;

        if (node.isEntity) {
            fill = '#02aed7'; stroke = '#0288a7'; textFill = '#ffffff';
        } else if (directTypes.indexOf(v) !== -1) {
            fill = '#e8f5e9'; stroke = '#43a047'; textFill = '#2e7d32';
        } else if (node.isSchema) {
            fill = '#fff3e0'; stroke = '#ef6c00'; textFill = '#e65100';
        } else {
            fill = '#e3f2fd'; stroke = '#1565c0'; textFill = '#0d47a1';
        }

        svg += '<g>';
        svg += '<rect x="' + x + '" y="' + y + '" width="' + node.width + '" height="' + node.height + '" rx="4" ry="4" fill="' + fill + '" stroke="' + stroke + '" stroke-width="1.5"/>';
        svg += '<text x="' + node.x + '" y="' + (node.y + 5) + '" text-anchor="middle" dominant-baseline="middle" font-family="Open Sans, sans-serif" font-size="12" fill="' + textFill + '">' + node.label.replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</text>';
        svg += '</g>';
    });

    svg += '</svg>';
    document.getElementById('home-dag').innerHTML = svg;
});
</script>
<?php endif; ?>

<h2>News</h2>

<div class="news_item">
    <div class="news_time">February, 2026</div>
    <div class="news_text">
        A new version of YAGO is in preparation! (And an overhaul to the Web page as well...)
    </div>
</div>


<div class="news_item">
    <div class="news_time">April, 2024</div>
    <div class="news_text">
        Our <a href="https://suchanek.name/work/publications/sigir-2024.pdf">paper</a> about <a href="https://yago-knowledge.org/downloads/yago-4-5">YAGO 4.5</a> got accepted at SIGIR 2024!
    </div>
</div>


<div class="news_item">
    <div class="news_time">April, 2024</div>
    <div class="news_text">
        We fixed several <a href="https://github.com/yago-naga/yago-4.5/issues">small bugs</a> in <a href="https://yago-knowledge.org/downloads/yago-4-5">YAGO 4.5</a> that were reported by our users.
    </div>
</div>

<div class="news_item">
    <div class="news_time">May, 2023</div>
    <div class="news_text">
        A <a href="https://yago-knowledge.org/downloads/yago-4-5">new version of YAGO, YAGO 4.5</a> is now available, which has a much richer taxonomy!
    </div>
</div>

<div class="news_item">
    <div class="news_time">April, 2023</div>
    <div class="news_text">
        YAGO receives the <a href="https://www.ouvrirlascience.fr/prix-science-ouverte-des-donnees-de-la-recherche/">French Open Research Award</a>!
    </div>
</div>

<div class="news_item">
    <div class="news_time">February, 2023</div>
    <div class="news_text">
        A 2022 revival version of <a href="https://yago-knowledge.org/downloads/yago-3">YAGO 3</a> is now available, with updated data from the French and English Wikipedia!
    </div>
</div>

<div class="news_item">
    <div class="news_time">April, 2022</div>
    <div class="news_text">
        We're building a new version of YAGO! If you have any suggestions, <a href=https://yago-knowledge.org/contributors>contact us</a>!
    </div>
</div>

<div class="news_item">
    <div class="news_time">February, 2022</div>
    <div class="news_text">
        YAGO is now available at <a href=https://zenodo.org/record/6022140>Zenodo</a>!
    </div>
</div>

<div class="news_item">
    <div class="news_time">March, 2020</div>
    <div class="news_text">
        The newest version of YAGO, <a href=downloads/yago-4>YAGO 4</a>, is now released!
    </div>
</div>

<div class="news_item">
    <div class="news_time">February, 2020</div>
    <div class="news_text">
        Our <a href=http://suchanek.name/work/publications/eswc-2020-yago.pdf>paper on YAGO 4</a> got accepted at <a href=https://2020.eswc-conferences.org/>ESWC 2020</a>
    </div>
</div>


<div class="news_item">
    <div class="news_time">May, 2018</div>
    <div class="news_text">
        The original YAGO paper (WWW 2007) has won the Seoul Test of Time Award of The Web Conference 2018 (WWW
        2018).
        For more details check the <a rel="noreferrer noopener"
                                      href="https://www.mpi-inf.mpg.de/news/press-releases/2018/forscher-des-saarbruecker-max-planck-instituts-von-der-world-wide-web-conference-fuer-pionierarbeit-geehrt/">webpage</a>.
    </div>
</div>

<div class="news_item">
    <div class="news_time">August 31, 2017</div>
    <div class="news_text">YAGO goes Open Source! Ten years after the first YAGO publication, the YAGO code is now
        available for anyone to
        work with. Get it on GitHub!
    </div>
</div>

<div class="news_item">
    <div class="news_time">August 22, 2017</div>
    <div class="news_text">The YAGO2 paper published in the Artificial Intelligence Journal (AIJ) receives the AIJ
        Prominent Paper Award.
    </div>
</div>

<div class="news_item">
    <div class="news_time">September 15, 2015</div>
    <div class="news_text">An evaluated version of YAGO3 is available for download.</div>
</div>

<div class="news_item">
    <div class="news_time">March 24, 2015</div>
    <div class="news_text">YAGO3 is released for download.</div>
</div>

<div class="news_item">
    <div class="news_time">October 14, 2014</div>
    <div class="news_text">The multilingual YAGO3 was accepted for CIDR 2015.</div>
</div>

<div class="news_item">
    <div class="news_time">March 22, 2013</div>
    <div class="news_text">The demo about the functionalities of YAGO2s got accepted for WWW 2013. See the webpage for
        detailed info!
    </div>
</div>

<div class="news_item">
    <div class="news_time">Dec 3, 2012</div>
    <div class="news_text">New demo paper about YAGO2s at BTW 2013.</div>
</div>

<div class="news_item">
    <div class="news_time">Oct 16, 2012</div>
    <div class="news_text">The new version of YAGO, YAGO2s, is out! YAGO2s brings a number of exciting novelties.</div>
</div>

<div class="news_item">
    <div class="news_time">Mar 23, 2012</div>
    <div class="news_text">Our journal article on YAGO2 got accepted for the special issue of the AI journal.</div>
</div>

