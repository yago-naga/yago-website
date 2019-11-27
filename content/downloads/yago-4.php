<div class="code-container">
    <h1>YAGO4</h1>
    <p>
       YAGO 4 is the latest version of the YAGO knowledge base. It is based on Wikidata, and refines the data as follows:
       <ol>
       <li>All entity identifiers and property identifiers are human-readable.
       <li>The top-level hierarchy comes from schema.org
       <li>All classes have at least 10 instances
       <li>Properties come from schema.org
       <li>YAGO 4 contains logical constraints in the form of SHACL
       </ol>
    </p>
</div>

<div class="code-container">
    <h1>How to use YAGO 4</h1>
    <p>
        YAGO 4 is an RDFS knowledge base. It is a collection of facts, each of which consists of a subject, a predicate, and an object &mdash; as in <code>&lt;Elvis_Presley&gt; rdf:type &lt;person&gt;</code>. 
    </p>
    <p>
    YAGO puts each entity into at least one class. The classes form a taxonomy, where the higher classes are taken from <a href=http://schema.org>schema.org</a>, and the lower classes are a selection of classes from <a href=http://wikidata.org>Wikidata</a>.  The highest class is <code>schema:Thing</code>.
    </p>
    <p>
    The facts come from <a href=http://wikidata.org>Wikidata</a>, and the predicates have been mapped manually to the predicates of <a href=http://schema.org>schema.org</a>. Facts whose predicates could not be mapped were omitted. All predicates, all classes, and most entities have human-readable names. For the entities and classes, these come from the corresponding English Wikipedia article. If no such article exists, the name comes from the English label of Wikidata, concatenated with the Wikidata identifier. If no such label exists, we use the Wikidata identifier.    YAGO entities are mapped with <code>owl:sameAs</code> to Wikidata and DBpedia.
    </p>
    <p>
    YAGO 4 comes with SHACL constraints that specify the disjointness of certain classes, as well as the domains, ranges, and cardinalities of relations.
    </p>
</div>

<div class="code-container">
    <h1>Data</h1>
    <p>
        YAGO 4 licensed under a <a href=https://creativecommons.org/licenses/by-sa/3.0/>Creative Commons Attribution-ShareAlike</a> by the YAGO team of Télécom Paris and the Max Planck Institute for Informatics. 
    </p>
</div>

<div class="code-container">
    <h1>Code</h1>
    <p>
        If you are just interested in the data of YAGO, there is no need to use the present code repository. You can
        download data of YAGO above.
    </p>
    <p>
        The source code of YAGO is a Java project that ingests facts from Wikidata, and transforms them into YAGO. If you run the code yourself, you can add other sources or modify the generation of the knowledge base. The YAGO 4 source code is
        available at <a rel="noreferrer noopener" target="_blank" href=https://github.com/yago-naga/yago4>Github</a>. It
        is licensed under the <a href=https://www.gnu.org/licenses/gpl-3.0.en.html>GNU General Public License</a>, version 3 or later.
    </p>
</div>

<div class="code-container">
<h1>Acknowledgements</h1>

<p>YAGO can only be so large because it is based on other sources. We would like to thank</p>
<ul class="browser-default">
    <li>
        the creators and contributors of <a href="http://wikidata.org" rel="noreferrer noopener" target="_blank">Wikidata</a>.
        <br/>
        Thank you for having and implementing such an ambitious vision of building a “Wikipedia for machines”, and thank
        you for keeping it open!
    </li>
    <li>
        the numerous voluntary editors of <a href="http://wikipedia.org" rel="noreferrer noopener" target="_blank">Wikipedia</a>.
        <br/>
        Thank you for giving mankind such a wonderful huge encyclopedia!
    </li>
    <li>
        the team of <a href="http://schema.org" rel="noreferrer noopener" target="_blank">Schema.org</a>, who created the taxonomy and the properties that we use in YAGO 4.
    </li>
</ul>
</div>