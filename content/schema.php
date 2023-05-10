<h1>Schema</h1>

This is the top-level taxonomy of classes of YAGO. Please find detailed descriptions in our <a href="https://yago-knowledge.org/data/yago4.5/design-document.pdf">Design Document</a>.

 <ul style="padding-left: 3em">
        <li><details><summary>schema:Thing</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:sameAs
<li>- rdfs:label
<li>- schema:image
<li>- schema:alternateName
<li>- schema:url
<li>- rdfs:comment
<li>- schema:mainEntityOfPage
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- schema:about
<li>- schema:influencedBy
<li>- schema:owns
<li>- schema:about
<li>- schema:influencedBy
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
<li><details><summary>schema:Taxon</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:parentTaxon
<li>- yago:consumes
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- schema:parentTaxon
<li>- yago:consumes
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>schema:Organization</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:numberOfEmployees
<li>- schema:leader
<li>- schema:location
<li>- schema:motto
<li>- schema:memberOf
<li>- schema:memberOf
<li>- schema:address
<li>- schema:leiCode
<li>- schema:founder
<li>- schema:dateCreated
<li>- schema:parentOrganization
<li>- schema:locationCreated
<li>- schema:award
<li>- schema:logo
<li>- schema:duns
<li>- schema:dissolutionDate
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- schema:worksFor
<li>- schema:alumniOf
<li>- schema:memberOf
<li>- schema:memberOf
<li>- schema:productionCompany
<li>- schema:parentOrganization
<li>- schema:productionCompany
<li>- schema:publisher
<li>- schema:memberOf
<li>- schema:recordLabel
<li>- schema:memberOf
<li>- schema:affiliation
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
<li><details><summary>schema:Airline</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:icaoCode
<li>- schema:iataCode
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"></ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>schema:EducationalOrganization</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- yago:studentsCount
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"></ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>schema:PerformingGroup</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- yago:director
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"></ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
<li><details><summary>schema:MusicGroup</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:recordLabel
<li>- schema:influencedBy
<li>- yago:notableWork
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"></ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
</ul></details>
<li><details><summary>schema:Corporation</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- schema:manufacturer
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
</ul></details>
<li><details><summary>schema:CreativeWork</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:award
<li>- schema:inLanguage
<li>- schema:about
<li>- schema:contentLocation
<li>- schema:dateCreated
<li>- schema:author
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- yago:notableWork
<li>- yago:appearsIn
<li>- yago:notableWork
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
<li><details><summary>schema:TVSeries</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:actor
<li>- schema:numberOfSeasons
<li>- schema:countryOfOrigin
<li>- schema:director
<li>- schema:numberOfEpisodes
<li>- schema:musicBy
<li>- schema:productionCompany
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"></ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>schema:Book</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:publisher
<li>- schema:editor
<li>- schema:illustrator
<li>- schema:isbn
<li>- schema:numberOfPages
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"></ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>schema:Movie</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:duration
<li>- schema:director
<li>- schema:productionCompany
<li>- schema:musicBy
<li>- schema:actor
<li>- schema:countryOfOrigin
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"></ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>schema:Newspaper</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:publisher
<li>- schema:sponsor
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"></ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>schema:MusicComposition</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:iswcCode
<li>- schema:composer
<li>- schema:lyricist
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"></ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
</ul></details>
<li><details><summary>schema:Person</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:spouse
<li>- yago:academicDegree
<li>- schema:birthDate
<li>- schema:nationality
<li>- schema:gender
<li>- schema:worksFor
<li>- schema:children
<li>- yago:beliefSystem
<li>- schema:affiliation
<li>- schema:owns
<li>- schema:birthPlace
<li>- schema:deathPlace
<li>- schema:alumniOf
<li>- schema:memberOf
<li>- schema:award
<li>- schema:knowsLanguage
<li>- schema:deathDate
<li>- schema:homeLocation
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- schema:leader
<li>- schema:founder
<li>- schema:director
<li>- schema:actor
<li>- yago:doctoralAdvisor
<li>- schema:illustrator
<li>- schema:leader
<li>- schema:children
<li>- schema:performer
<li>- yago:studentOf
<li>- yago:director
<li>- schema:spouse
<li>- schema:actor
<li>- schema:editor
<li>- schema:director
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>schema:Event</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:about
<li>- schema:location
<li>- schema:startDate
<li>- schema:sponsor
<li>- yago:follows
<li>- yago:participant
<li>- schema:endDate
<li>- schema:organizer
<li>- schema:superEvent
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- yago:playsIn
<li>- yago:follows
<li>- yago:candidateIn
<li>- schema:superEvent
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>schema:Intangible</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"></ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
<li><details><summary>yago:Award</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- yago:conferredBy
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- yago:academicDegree
<li>- schema:award
<li>- schema:award
<li>- schema:award
<li>- schema:award
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>yago:Gender</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- schema:gender
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>schema:Language</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- schema:inLanguage
<li>- schema:officialLanguage
<li>- schema:knowsLanguage
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
</ul></details>
<li><details><summary>schema:Place</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:geo
<li>- schema:containedInPlace
<li>- schema:lowestPoint
<li>- schema:elevation
<li>- schema:highestPoint
<li>- schema:neighbors
<li>- schema:area
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- schema:location
<li>- schema:location
<li>- schema:administrates
<li>- schema:containedInPlace
<li>- schema:birthPlace
<li>- schema:lowestPoint
<li>- schema:deathPlace
<li>- yago:terminus
<li>- schema:highestPoint
<li>- schema:contentLocation
<li>- schema:locationCreated
<li>- schema:neighbors
<li>- schema:homeLocation
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
<li><details><summary>schema:Landform</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"></ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
<li><details><summary>schema:BodyOfWater</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- yago:flowsInto
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- yago:flowsInto
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
</ul></details>
<li><details><summary>schema:AdministrativeArea</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:leader
<li>- schema:administrates
<li>- schema:motto
<li>- schema:postalCode
<li>- schema:memberOf
<li>- yago:replaces
<li>- schema:demonym
<li>- schema:dateCreated
<li>- schema:populationNumber
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- yago:replaces
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
<li><details><summary>schema:City</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- yago:capital
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
<li><details><summary>schema:Country</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:humanDevelopmentIndex
<li>- schema:officialLanguage
<li>- yago:capital
<li>- schema:unemploymentRate
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- schema:countryOfOrigin
<li>- schema:nationality
<li>- schema:countryOfOrigin
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
</ul></details>
</ul></details>
<li><details><summary>schema:Product</summary>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Properties:<ul style="padding-left: 3em">
<li>- schema:material
<li>- schema:manufacturer
<li>- schema:dateCreated
<li>- schema:gtin
<li>- schema:award
</ul>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Appears as object of:<ul style="padding-left: 3em"><li>- schema:material
</ul>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subclasses:<ul style="padding-left: 3em">
</ul></details>
</ul></details>
</ul>