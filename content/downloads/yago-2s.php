<div class="code-container">
    <h1>YAGO 2s</h1>

    <p>YAGO 2s is an improved version of YAGO 2, with the following main characteristics:</p>

    <ul class="browser-default">
        <li>
            YAGO2s is stored natively in Turtle, making it completely RDF/OWL compliant while still maintaining the fact
            identifiers that are unique to YAGO.
        </li>
        <li>
            The YAGO2s architecture enables cooperation of several contributors, facilitates debugging and maintenance.
            The data is divided into themes, so that users can download only particular pieces of YAGO (“YAGO à la
            carte”).
        </li>

        <li>YAGO2s contains thematic domains such as “music” or “science” from WordNet Domains, which gives a topic structure to YAGO.</li>
    </ul>

    <p>
        As all major releases, the accuracy of YAGO2s has been manually evaluated, proving an accuracy of 95% with
        respect
        to Wikipedia.
    </p>

    <p>
        If you use YAGO2s for scientific purposes, please cite our paper:
    </p>
    <blockquote>
        <p>
            <a rel="noreferrer noopener" target="_blank" href="http://people.mpi-inf.mpg.de/~jbiega/">Joanna “Asia”
                Biega</a>,
            <a rel="noreferrer noopener" target="_blank" href="http://www.mpi-inf.mpg.de/~ekuzey/">Erdal Kuzey</a>,
            <a rel="noreferrer noopener" target="_blank" href="https://suchanek.name">Fabian M. Suchanek</a>:
            <br/>
            <b>Inside YAGO2s: A Transparent Information Extraction Architecture</b>
            <a class="pdf-download-icon" rel="noreferrer noopener" target="_blank"
               href="https://suchanek.name/work/publications/www2013demo.pdf">
                <img src="<?php site_url(); ?>/assets/images/pdf.png" width="15"/>
            </a>
            <br/>
            Demo at the <a rel="noreferrer noopener" target="_blank" href="http://www.www2013.org/">
                World Wide Web Conference (WWW)</a>, 2013
        </p>
    </blockquote>

</div>

<div class="code-container">
    <h1>How to use YAGO 2s</h1>

    <p>
        YAGO classifies each entity into a taxonomy of classes. Every entity is an instance of one or multiple classes.
        Every class (except the root class) is a subclass of one or multiple classes. This yields a hierarchy of classes
        —
        the taxonomy. The YAGO taxonomy is the backbone of the ontology, and is designed with much care and attention to
        correctness.
        For those interested in the details of that taxonomy, we provide here a more in-depth explanation of the
        classes.
        The taxonomy consists of 4 layers:
    </p>

    <ol  class="browser-default">
        <li>
            The root node of the taxonomy is <code>rdfs:Resource</code>. It includes entities, but also properties,
            literals, etc.
            <code>rdfs:Resource</code> has a subclass <code>owl:Thing</code>, which is the class of things (entities).
        </li>

        <li>
            Under <code>owl:Thing</code>, there is the class taxonomy from WordNet. Each class name is of the form
            <code>&lt;wordnet_XXX_YYY&gt;</code>,
            where XXX is the name of the concept (e.g., singer), and YYY is the WordNet 3.0 synset id of the concept
            (e.g., 110599806). For example, the class of singers is <code>&lt;wordnet_singer_110599806&gt;</code>. Each
            class is
            connected to its more general class by the <code>rdfs:subclassOf</code> relationship.
        </li>

        <li>
            The middle layer of the taxonomy consists of classes that have been derived from Wikipedia categories. For
            example, one class is <code>&lt;wikicategory_American_rock_singers&gt;</code>, derived from the Wikipedia
            category American rock singers. Each of these classes is connected to one class of the WordNet layer by a
            <code>rdfs:subclassOf</code> relationship. In the example,
            <code>&lt;wikicategory_American_rock_singers&gt;</code> <code>rdfs:subclassOf</code>
            <code>&lt;wordnet_singer_110599806&gt;</code>. Not all Wikipedia categories become classes in YAGO.
        </li>

        <li>
            The lowest layer of the taxonomy is the layer of instances. Instances comprise individual entities such as
            rivers, people, or movies. For example, this layer contains <code>&lt;Elvis_Presley&gt;</code>. Each
            instance is connected to one or multiple classes of the higher layers by the relationship rdf:type. In the
            example: <code>&lt;Elvis_Presley&gt;</code> <code>rdf:type</code> <code>&lt;wikicategory_American_rock_singers&gt;</code>.
        </li>
    </ol>

    <p>
        This way, you can walk from the instance up to its class by rdf:type, and then further up by <code>rdfs:subclassOf</code>.
    </p>

    <p>
        In YAGO (as in RDF), each fact consists of a subject, a predicate, and an object. Every fact can have a fact id.
        The fact id is simply computed as a hash from the subject, predicate, and object of the fact. For example, the
        fact &lt;Elvis_Presley&gt; rdf:type &lt;person&gt; could have the fact identifier &lt;id_42&gt;.
        YAGO contains facts about these fact identifiers. For example, YAGO contains
    </p>

    <ul>
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
        YAGO2s is licensed under a <a href=https://creativecommons.org/licenses/by/3.0/>Creative Commons Attribution 3.0 License</a> by the YAGO team of the Max-Planck Institute
        for
        Informatics. The exact version number of this data is 2.5.3. The data was extracted from the 2012-12-01 version
        of Wikipedia.
    </p>

    <p>
        The YAGO2s knowledge base is a set of independent modular full-text files, which together constitute the
        knowledge base. This data is available in three formats:
    </p>

    <ul  class="browser-default">
        <li>The <a rel="noreferrer noopener" target="_blank"
                   href=/data/yago2s/yago-2.5.3-native.7z>TSV format</a>
            contains 5 columns: fact identifier, subject, predicate, object, numerical value of the object
            (if applicable).
		<li>The <a rel="noreferrer noopener" target="_blank"
                   href=/data/yago2s/yago-2.5.3-turtle-simple.7z>Simple Turtle format</a> is a <a href=http://www.w3.org/TR/turtle/>Turtle</a> export of the TSV format, without the meta-facts (“facts about facts”).
        <li>
            The <a rel="noreferrer noopener" target="_blank"
                   href=/data/yago2s/yago-2.5.3-turtle.7z>Full Turtle format</a> is a <a href=http://www.w3.org/TR/turtle/>Turtle</a> export of YAGO that also contains the meta-facts. This is achieved by a backwards-compatible custom variant of Turtle, which stores the fact identifiers in a comment line before the actual fact. This means  that they are not visible (and no obstacle) to systems that do not support this type of comments.
    </ul>
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
    <li>the <a href=http://www.lexvo.org/uwn/>Universal WordNet</a>, which provided YAGO with multilingual labels for classes</li>
    <li>the <a href=http://wndomains.fbk.eu/>WordNet Domains</a> project for categorizing WordNet synsets into thematic domains.</li>
    </ul>
 </div>   
