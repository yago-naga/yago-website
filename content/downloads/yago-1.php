<div class="code-container">
    <h1>YAGO1</h1>

    <p>
        This is the 2008 version of YAGO. It knows more than 2 million entities (like persons, organizations, cities,
        etc.).
        It knows 20 million facts about these entities. This version of YAGO includes the data extracted from the
        categories
        and infoboxes of Wikipedia, combined with the taxonomy of WordNet. YAGO1 was manually evaluated, and found to
        have
        an accuracy of 95% with respect to the extraction source.
    </p>

    <p>If you use YAGO1 for scientific purposes, please cite our paper:</p>

    <blockquote>
        <p>
            <a rel="noreferrer noopener" target="_blank" href="https://suchanek.name">Fabian M. Suchanek</a>,
            <a rel="noreferrer noopener" target="_blank" href="http://www.mpii.mpg.de/~kasneci">Gjergji Kasneci</a>,
            <a rel="noreferrer noopener" target="_blank" href="http://www.mpii.mpg.de/~weikum">Gerhard Weikum</a>:
            <br/>
            <b>Yago - A Core of Semantic Knowledge</b>
            <a class="pdf-download-icon" rel="noreferrer noopener" target="_blank"
               href="https://suchanek.name/work/publications/www2007.pdf">
                <img src="<?php site_url(); ?>/assets/images/pdf.png" width="15"/>
            </a>
            <br/>
            Full paper at the <a rel="noreferrer noopener" target="_blank" href="http://www2007.org/">
                World Wide Web Conference (WWW)</a>
        </p>
    </blockquote>
</div>

<div class="code-container">
    <h1>How to use YAGO1</h1>
    <p>
        YAGO classifies each entity into a taxonomy of classes. Every entity is an instance of one or multiple classes.
        Every class (except the root class) is a subclass of one or multiple classes. This yields a hierarchy of classes
        — the taxonomy. The YAGO taxonomy is the backbone of the ontology, and is designed with much care and attention
        to correctness.
    </p>

</div>

<div class="code-container">
    <h1>Data</h1>
    <p>
        The YAGO1 knowledge base is licensed under the GNU Free Documentation License,
        see <a rel="noreferrer noopener" target="_blank" href="https://www.gnu.org/copyleft/fdl.html">https://www.gnu.org/copyleft/fdl.html</a>.
        The exact version number of this data is 1.0.0. The data was extracted from the 2008-10-01 version of Wikipedia.
    </p>

    <p>
        YAGO1 comes in 3 different formats:
    </p>

    <ul>
        <li><a rel="noreferrer noopener" target="_blank"
               href=http://resources.mpi-inf.mpg.de/yago-naga/yago1_yago2/download/yago1/YAGO1.0.0/yago.zip>native</a>
            (1GB)
        <li><a rel="noreferrer noopener" target="_blank"
               href=http://resources.mpi-inf.mpg.de/yago-naga/yago1_yago2/download/yago1/YAGO1.0.0/rdfs.zip>RDF/XML</a>
            (1GB)
        <li><a rel="noreferrer noopener" target="_blank"
               href=http://resources.mpi-inf.mpg.de/yago-naga/yago1_yago2/download/yago1/YAGO1.0.0/n3.zip>N3</a>
            (250Mb)
    </ul>

    <p>While RDF/XML and N3 are the standard formats, the native format is as follows:</p>

    <ul class="browser-default">
        <li>
            The folder "facts" contains the main data
            There is one subfolder for each relation
            Each subfolder contains several files.
            Each of these files contains lines of the form
            <code>&lt;factId&gt; TAB &lt;arg1&gt; TAB &lt;arg2&gt; TAB &lt;confidence&gt;</code>.
            One such line means that <code>&lt;arg1&gt;</code> and <code>&lt;arg2&gt;</code> stand in the relation
            given by the subfolder, with an accumulated confidence <code>&lt;confidence&gt;</code>.
        </li>

        <li>
            The folder "Entities" contains all entities
            There are several files, each of which contains lines of the form
            <code>&lt;entity&gt; TAB &lt;isConcept&gt; TAB &lt;URL&gt;</code>
            where <code>&lt;isConcept&gt;</code> is either true or false and tells whether the
            entity is a concept. <code>&lt;URL&gt;</code> is an URL that describes the entity
            (or null). This table is compiled as additional information from the
            relationships <code>TYPE</code> and <code>DESCRIBES</code>.
        </li>
    </ul>

    <p>
        Note that, if a folder contains multiple files, all of these files are
        part of the knowledge base.
    </p>
</div>
