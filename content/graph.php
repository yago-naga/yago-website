<?php require 'includes/sparql.php'; ?>
<h1>Graph visualization</h1>

<div class="alchemy" id="alchemy"></div>
<div id="cytoscape" style="width: 100%; height: 1000px;"></div>
<script type="text/javascript"
        src="//tools-static.wmflabs.org/cdnjs/ajax/libs/cytoscape/3.9.2/cytoscape.min.js"></script>
<script>
    window.onload = function () {
        var start = "http://yago-knowledge.org/resource/Elvis_Presley";
        var userLang = 'en'; //(navigator.language || navigator.userLanguage || 'en').split("-")[0];
        var displayedNodes = [];
        var prefixes = <?php echo json_encode($PREFIXES); ?>;

        function doSparqlQuery(query) {
            return $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo config('sparql_endpoint'); ?>",
                headers: {
                    "Content-Type": "application/sparql-query"
                },
                data: query
            });
        }

        function encodeId(id) {
            var newId = '';
            for (var i in id) {
                var c = id[i];
                if (('a' <= c && c <= 'z') || ('A' <= c && c <= 'Z') || ('0' <= c && c <= '9')) {
                    newId += c;
                }
            }
            return newId;
        }

        function encodeUri(uri) {
            for (var prefix in prefixes) {
                if (uri.startsWith(prefixes[prefix])) {
                    return uri.replace(prefixes[prefix], prefix + ':');
                }
            }
            return uri;
        }

        function getRootNode(uri) {
            return doSparqlQuery('SELECT ?id ?label WHERE { BIND(<' + uri + '> AS ?id) OPTIONAL { ?id <http://www.w3.org/2000/01/rdf-schema#label>|<http://schema.org/name> ?label FILTER(LANG(?label) = "' + userLang + '") } }')
                .then(function (sparql) {
                    for (var i in sparql.results.bindings) {
                        var binding = sparql.results.bindings[i];
                        return [{
                            group: 'nodes',
                            data: {
                                id: encodeId(binding.id.value),
                                uri: binding.id.value,
                                label: encodeUri(binding.id.value),
                                level: 0
                            }
                        }]
                    }
                })
        }

        function getNeighbours(uri, level) {
            displayedNodes.push(uri);
            return doSparqlQuery('SELECT ?edge ?node ?edgeLabel ?nodeLabel WHERE { <' + uri + '> ?edge ?node FILTER(isIRI(?node) && EXISTS { ?node ?p ?o }) OPTIONAL { ?edge <http://www.w3.org/2000/01/rdf-schema#label>|<http://schema.org/name> ?edgeLabel FILTER(LANG(?edgeLabel) = "' + userLang + '") } OPTIONAL { ?node <http://www.w3.org/2000/01/rdf-schema#label>|<http://schema.org/name> ?nodeLabel FILTER(LANG(?nodeLabel) = "' + userLang + '") } }')
                .then(function (sparql) {
                    var results = [];
                    for (var i in sparql.results.bindings) {
                        var binding = sparql.results.bindings[i];
                        results.push({
                            group: 'nodes',
                            data: {
                                id: encodeId(binding.node.value),
                                uri: binding.node.value,
                                label: encodeUri(binding.node.value),
                                level: level
                            }
                        });
                        results.push({
                            group: 'edges',
                            data: {
                                id: encodeId(uri) + ':' + encodeId(binding.edge.value) + ':' + encodeId(binding.node.value),
                                uri: binding.edge.value,
                                source: encodeId(uri),
                                target: encodeId(binding.node.value),
                                label: encodeUri(binding.edge.value)
                            }
                        });
                    }
                    return results;
                })
        }

        var cy = cytoscape({
            container: document.getElementById('cytoscape'),
            style: [
                {
                    selector: 'node',
                    style: {
                        'label': 'data(label)',
                        'text-background-color': 'white',
                        'text-background-opacity': 1,
                        'text-background-shape': 'roundrectangle'
                    }
                },
                {
                    selector: 'edge',
                    style: {
                        'curve-style': 'bezier',
                        'label': 'data(label)',
                        'text-background-color': 'white',
                        'text-background-opacity': 1,
                        'text-background-shape': 'roundrectangle',
                        'text-rotation': 'autorotate',
                        'target-arrow-shape': 'triangle'
                    }
                }
            ]
        });

        function addElements(elements, level) {
            cy.add(elements);
            /*cy.layout({
                name: 'concentric',
                animate: true,
                minNodeSpacing: 60,
                concentric: function( node ) {
                    return 100 - node.data('level');
                },
                levelWidth: function(nodes) {
                    return 1;
                },
                fit: true
            }).run();*/
            cy.layout({
                name: 'cose',
                animate: true,
                numIter: 10000,
                fit: true,
                nodeRepulsion: 400000,
                nodeOverlap: 10,
                idealEdgeLength: function (edge) {
                    return 10 * edge.data('label').length;
                },
                edgeElasticity: 100,
                nestingFactor: 5,
                gravity: 80,
                initialTemp: 200,
                coolingFactor: 0.99
            }).run();
            cy.nodes().on('click', function (e) {
                var uri = e.target.data('uri');
                if (displayedNodes.indexOf(uri) === -1) {
                    getNeighbours(e.target.data('uri'), level + 1).then(function (elements) {
                        addElements(elements, level + 1);
                    });
                }
            });
        }

        getRootNode(start).then(function (elements1) {
            getNeighbours(start, 1).then(function (elements2) {
                addElements(elements1.concat(elements2), 1);
            });
        });
    };
</script>