<div class="code-container">
    <h1>YAGO 2</h1>

    <p>YAGO 2 is an improved version of the original YAGO knowledge base:</p>

    <ul class="browser-default">
        <li>
            YAGO 2 is anchored in time and space. YAGO 2 attaches a temporal dimension and a
            spacial dimension to many of its facts and entities.
        </li>

        <li>
            YAGO 2 is particularly suited for disambiguation purposes, as it contains a large number of names for
            entities. It also knows the gender of people.
        </li>

        <li>
            As all major releases, the accuracy of YAGO 2 has been manually evaluated, proving an accuracy of 95% with
            respect to Wikipedia. Every relation is annotated with its confidence value.
        </li>
    </ul>

    <p>
        If you use YAGO 2 for scientific purposes, please cite our paper:
    </p>
    <blockquote>
        <p>
            <a rel="noreferrer noopener" target="_blank" href="http://www.mpi-inf.mpg.de/~jhoffart">
                Johannes Hoffart</a>,
            <a rel="noreferrer noopener" target="_blank" href="https://suchanek.name">
                Fabian M. Suchanek</a>,
            <a rel="noreferrer noopener" target="_blank" href="http://mpi-inf.mpg.de/~kberberi/">
                Klaus Berberich</a>,
            <a href="http://www.mpii.mpg.de/~weikum">Gerhard Weikum</a>:
            <br/>
            <b>YAGO2: A Spatially and Temporally Enhanced Knowledge Base from Wikipedia</b>
            <a class="pdf-download-icon" rel="noreferrer noopener" target="_blank"
               href="https://suchanek.name/work/publications/aaaij.pdf">
                <img src="<?php site_url(); ?>/assets/images/pdf.png" width="15"/>
            </a>
            <br/>
            Journal article in the
            <a rel="noreferrer noopener" target="_blank" href="http://www.cl.uni-heidelberg.de/~ponzetto/aij/">
                Artificial Intelligence Journal</a>, 2013
        </p>
    </blockquote>
</div>

<div class="code-container">
    <h1>How to use YAGO 2</h1>
    <p>
        YAGO classifies each entity into a taxonomy of classes. Every entity is an instance of one or multiple classes.
        Every class (except the root class) is a subclass of one or multiple classes. This yields a hierarchy of classes
        —
        the taxonomy. The YAGO taxonomy is the backbone of the ontology, and is designed with much care and attention to
        correctness.
    </p>

    <p>
        In YAGO (as in RDF), each fact consists of a subject, a predicate, and an object. Every fact can have a fact id.
        For example, the fact &lt;Elvis_Presley&gt; rdf:type &lt;person&gt; could have the fact identifier &lt;id_42&gt;.
        YAGO contains facts about these fact identifiers. For example, YAGO contains
    </p>
    <ul >
        <li><code>&lt;id_42&gt; &lt;occursSince&gt; "1935-01-08"</code></li>
        <li><code>&lt;id_42&gt; &lt;occursUntil&gt; "1977-08-16"</code></li>
        <li><code>&lt;id_42&gt; &lt;extractionSource&gt; &lt;http://en.wikipedia.org/Elvis_Presley&gt;</code></li>
    </ul>

    <p>
        These facts mean that Elvis was a person from the year 1935 to the year 1977, and that this fact was found in
        Wikipedia.
    </p>

</div>

<div class="code-container">
    <h1>Data</h1>
    <p>
        The YAGO 2 knowledge base is licensed under a <a href=https://creativecommons.org/licenses/by/3.0/>Creative Commons Attribution 3.0 License</a> by the YAGO team of the
        Max-Planck Institute for
        Informatics. The version number of this data is 2.3.0. The data was extracted from the 2010-08-17 version of
        Wikipedia.
    </p>

    <p>
        YAGO 2 is available in two data formats:</p>
        <ul  class="browser-default">
        <li><a rel="noreferrer noopener" target="_blank" href=http://resources.mpi-inf.mpg.de/yago-naga/yago1_yago2/download/yago2/YAGO2.3.0__yago2full_20120109/YAGO2.3.0__yago2full_20120109.7z>native</a>
        <li><a rel="noreferrer noopener" target="_blank"
        href=http://resources.mpi-inf.mpg.de/yago-naga/yago1_yago2/download/yago2/YAGO2.3.0__yago2full_20120109/YAGO2.3.0__yago2full_20120109.rdfs.7z>RDF</a>.
           </ul>

    <p>
        While RDF is the standard for triple data,
        the native format has the advantage that it stores the fact identifier. The native format consists of a set of
        files, which together constitute the knowledge base. The files that start with an underscore are internal files
        that will be of little use to the end-user. The other files are named after the relation they contain. For
        example, the file “ismarriedto.tsv” is a TSV file that contains 3 columns: a fact id, a subject, and an object
        &mdash; meaning that the subject was married to the object, and that this fact has the given fact id.
    </p>
</div>

<div class="code-container">
    <h1>Acknowledgements</h1>
<p>YAGO can only be so large because it is based on other sources. We would like to thank</p>
<ul class="browser-default">
    <li>
        the numerous voluntary editors of <a href="https://wikipedia.org" rel="noreferrer noopener" target="_blank">Wikipedia</a>.
        Thank you for giving mankind such a wonderful huge encyclopedia!
    </li>
    <li>
        the team of <a href="http://www.geonames.org/" rel="noreferrer noopener" target="_blank">Geonames</a>.
        Thank you for creating this marvellous collection of geographical data, and thank you for providing this work for free!
    </li>    
    <li>
        the creators of <a href="http://wordnet.princeton.edu" rel="noreferrer noopener"
                           target="_blank">WordNet</a>.
        Thank you for organizing and analyzing the English language in such a diligent way and thank you for making your
        work available for free!
    </li>
    <li>the <a href=http://www.lexvo.org/uwn/>Universal WordNet</a>, which provided YAGO with multilingual labels for classes.</li>    
    </ul>
 </div>   
