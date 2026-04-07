/**
 * Shared DAG (directed acyclic graph) renderer for class hierarchy visualization.
 * Used by both the homepage demo and the resource page taxonomy card.
 *
 * Requires: dagre.js (loaded via CDN in footer.php)
 *
 * @param {Object} config
 * @param {string}   config.containerId   - DOM element ID to render SVG into
 * @param {Object}   config.nodes         - Map of URI → {label, url, isSchema}
 * @param {Array}    config.edges         - Array of {child, parent} URI pairs
 * @param {string}   config.entityUri     - URI of the focal entity
 * @param {string}   config.entityLabel   - Display label of the focal entity
 * @param {Array}    config.directTypes   - URIs of the entity's direct rdf:type values
 * @param {boolean}  [config.isClass]     - If true, entity is a class (no separate entity node added)
 * @param {string}   [config.entityUrl]   - URL for the entity node link (null = no link)
 * @param {boolean}  [config.linkNodes]   - If true, class nodes are wrapped in <a> links
 * @param {string}   [config.arrowId]     - Unique ID for the SVG arrow marker (default: 'dag-arrow')
 */
function renderDag(config) {
    if (typeof dagre === 'undefined') return;

    var nodes = config.nodes;
    var edges = config.edges;
    var entityUri = config.entityUri;
    var entityLabel = config.entityLabel;
    var directTypes = config.directTypes || [];
    var isClass = config.isClass || false;
    var linkNodes = config.linkNodes || false;
    var arrowId = config.arrowId || 'dag-arrow';

    var g = new dagre.graphlib.Graph();
    g.setGraph({ rankdir: 'BT', nodesep: 20, ranksep: 40, marginx: 15, marginy: 15 });
    g.setDefaultEdgeLabel(function() { return {}; });

    // Measure text widths using a temporary off-screen SVG
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

    // Add taxonomy class nodes
    for (var uri in nodes) {
        var node = nodes[uri];
        var w = measureText(node.label) + 30;
        g.setNode(uri, { label: node.label, width: Math.max(w, 50), height: 30, url: node.url, isSchema: node.isSchema });
    }

    // Add the focal entity node (unless it's already a class in the graph)
    if (!isClass) {
        var ew = measureText(entityLabel) + 30;
        g.setNode(entityUri, { label: entityLabel, width: Math.max(ew, 50), height: 30, url: config.entityUrl || null, isEntity: true });
        for (var i = 0; i < directTypes.length; i++) {
            if (g.hasNode(directTypes[i])) {
                g.setEdge(entityUri, directTypes[i]);
            }
        }
    }

    // Add taxonomy edges
    for (var i = 0; i < edges.length; i++) {
        g.setEdge(edges[i].child, edges[i].parent);
    }

    document.body.removeChild(tmpSvg);
    dagre.layout(g);

    var graph = g.graph();
    var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' + graph.width + '" height="' + graph.height + '">';
    svg += '<defs><marker id="' + arrowId + '" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto">';
    svg += '<path d="M 0 0 L 10 5 L 0 10 z" fill="#9e9e9e" opacity="0.5"/></marker></defs>';

    // Render edges
    g.edges().forEach(function(e) {
        var edge = g.edge(e);
        var points = edge.points;
        if (points && points.length >= 2) {
            var d = 'M ' + points[0].x + ' ' + points[0].y;
            for (var j = 1; j < points.length; j++) {
                d += ' L ' + points[j].x + ' ' + points[j].y;
            }
            svg += '<g class="dag-edge"><path d="' + d + '" fill="none" stroke="#9e9e9e" stroke-width="1.2" marker-end="url(#' + arrowId + ')"/></g>';
        }
    });

    // Render nodes with color coding:
    //   Entity node (focal): blue    |  Direct types: green
    //   Schema.org classes: orange   |  YAGO classes: light blue
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

        var hasLink = linkNodes && !!node.url;

        svg += '<g class="dag-node">';
        if (hasLink) svg += '<a href="' + node.url + '">';
        svg += '<rect x="' + x + '" y="' + y + '" width="' + node.width + '" height="' + node.height + '" rx="4" ry="4" fill="' + fill + '" stroke="' + stroke + '" stroke-width="1.5"/>';
        svg += '<text x="' + node.x + '" y="' + (node.y + 5) + '" text-anchor="middle" dominant-baseline="middle" font-family="Open Sans, sans-serif" font-size="12" fill="' + textFill + '">' + node.label.replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</text>';
        if (hasLink) svg += '</a>';
        svg += '</g>';
    });

    svg += '</svg>';
    document.getElementById(config.containerId).innerHTML = svg;
}
