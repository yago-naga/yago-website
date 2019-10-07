<?xml version="1.0" encoding="UTF-8"?>
<!--- Transforms a SPARLQ query result about an entity into an SVG visualization. The query takes the following form:

PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <rdfs:>
PREFIX yago: <http://yago-knowledge.org/resource/>
PREFIX schema: <http://schema.org/>
PREFIX owl: 	<http://www.w3.org/2002/07/owl#>
 
SELECT ?s ?p ?o ?count WHERE {
 {
 SELECT DISTINCT ?s (rdfs:subClassOf AS ?p) ?o (1 AS ?count) WHERE {
   <http://yago-knowledge.org/resource/Elvis_Presley> rdf:type ?c .
   ?c rdfs:subClassOf* ?s .
   ?s rdfs:subClassOf ?o .
 }
 }
 UNION
 {
   SELECT ?s ?p (SAMPLE(?o) AS ?o) (COUNT(?o)
AS ?count) WHERE {
     BIND(<http://yago-knowledge.org/resource/Elvis_Presley> AS ?s)
     ?s ?p ?o .
     FILTER(?p != rdf:type)
   }
   GROUP BY ?s ?p
 }
}

http://yago.r2.enst.fr/sparql/query?query=PREFIX%20rdf:%20<http://www.w3.org/1999/02/22-rdf-syntax-ns#>%20PREFIX%20rdfs:%20<rdfs:>%20PREFIX%20yago:%20<http://yago-knowledge.org/resource/>%20PREFIX%20schema:%20<http://schema.org/>%20PREFIX%20owl:%20<http://www.w3.org/2002/07/owl#>%20%20%20SELECT%20%3Fs%20%3Fp%20%3Fo%20%3Fcount%20WHERE%20{%20%20{%20%20SELECT%20DISTINCT%20%3Fs%20(rdfs:subClassOf%20AS%20%3Fp)%20%3Fo%20(1%20AS%20%3Fcount)%20WHERE%20{%20%20%20%20<http://yago-knowledge.org/resource/Elvis_Presley>%20rdf:type%20%3Fc%20.%20%20%20%20%3Fc%20rdfs:subClassOf*%20%3Fs%20.%20%20%20%20%3Fs%20rdfs:subClassOf%20%3Fo%20.%20%20}%20%20}%20%20UNION%20%20{%20%20%20%20SELECT%20%3Fs%20%3Fp%20(SAMPLE(%3Fo)%20AS%20%3Fo)%20(COUNT(%3Fo)%20AS%20%3Fcount)%20WHERE%20{%20%20%20%20%20%20BIND(<http://yago-knowledge.org/resource/Elvis_Presley>%20AS%20%3Fs)%20%20%20%20%20%20%3Fs%20%3Fp%20%3Fo%20.%20%20%20%20%20%20FILTER(%3Fp%20!=%20rdf:type)%20%20%20%20}%20%20%20%20GROUP%20BY%20%3Fs%20%3Fp%20%20}%20}%20

-->

<!-- We need the node-set operator, which is either in the Microsoft namespace or in the EXSLT namespace, depending on the XSLT processor you use. Rename the prefix to "ex" for the namespace that corresponds to your processor. -->
<xsl:stylesheet
	xmlns="http://www.w3.org/2000/svg"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:ex_="urn:schemas-microsoft-com:xslt"
	xmlns:s="http://www.w3.org/2005/sparql-results#"
	xmlns:svg="http://www.w3.org/2000/svg"
	xmlns:ex="http://exslt.org/common" version="1.0" exclude-result-prefixes="xsl s svg ex ex_">
	<xsl:output method="xml" encoding="UTF-8" omit-xml-declaration="yes"/>
	<xsl:strip-space elements="*"/>
	
	<!-- Configuration: fontSize, the vertical distance between two taxonomy items, the radius of the star, and the width and height of the image -->
	<xsl:variable name="fontSize" select="30"/>
	<xsl:variable name="taxonomyDistance" select="60"/>
	<xsl:variable name="radius" select="450"/>
	<xsl:variable name="width" select="$radius*4"/>
	<xsl:variable name="height" select="$radius*4"/>
	<xsl:variable name="yagoUrl" select="'http://yago.r2.enst.fr/graph/'"/>

	<!-- Build indexes for sub- and superclasses -->
	<xsl:key name="getSubclasses" match="/s:sparql/s:results/s:result[s:binding[@name='p']/s:uri/text()='rdfs:subClassOf']" use="s:binding[@name='o']/s:uri/text()" />
	<xsl:key name="getSuperclasses" match="/s:sparql/s:results/s:result[s:binding[@name='p']/s:uri/text()='rdfs:subClassOf']" use="s:binding[@name='s']/s:uri/text()" />
	<xsl:variable name="entity" select="/s:sparql/s:results/s:result[s:binding[@name='p']/s:uri/text()!='rdfs:subClassOf']/s:binding[@name='s']/s:uri/text()"/> 
	
	<!-- Given a set of classes to do, and given a set of classes that are done, remove one class to be done, print it, link it to its superclasses by an arrow, and add all its subclasses to the classes to be done. Use  a tail recursion. -->
	<xsl:template name="makeTaxonomy">
		<xsl:param name="classesToDo"/>
		<xsl:param name="classesDone"/>
		<xsl:choose>
			<xsl:when test="$classesToDo/svg:class">
			
				<!-- Save the current class name, its y-coordinate, its superclasses and its subclasses into variables-->
				<xsl:variable name="currentClassName" select="$classesToDo/svg:class[1]/@name"/>
				<xsl:variable name="subClasses" select="key('getSubclasses', $currentClassName)/s:binding[@name='s']/s:uri"/>
				<xsl:variable name="superClasses" select="key('getSuperclasses', $currentClassName)/s:binding[@name='o']/s:uri"/>
				<xsl:variable name="y" select="$classesToDo/svg:class[1]/@y"/>
				
				<!-- The x-coordinate is the maximum "xEnd" value of all classes that have the same y-coordinate-->
				<xsl:variable name="x">
					<xsl:choose>
						<xsl:when test="$classesDone/svg:class[@y=$y]">
							<xsl:for-each select="$classesDone/svg:class[@y=$y]/@xEnd">
								<xsl:sort select="." data-type="number"/>
								<xsl:if test="position()=last()">
									<xsl:value-of select=". + 3*$fontSize"/>
								</xsl:if>
							</xsl:for-each>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="$fontSize"/>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				<!-- Print the class name -->
				<text x="{$x}" y="{$y}" font-size="{$fontSize}" fill="blue" >
					<xsl:value-of select="$currentClassName"/>
				</text>
				
				<!-- Link the class name to all superclasses by arrows -->
				<xsl:for-each select="$superClasses">
					<xsl:variable name="superClass" select="text()"/>
					<line stroke-width="{$fontSize*0.1}" opacity="0.2" stroke="black" x1="{$x+$fontSize*3}" y1="{$y+$fontSize*-1}" x2="{$classesDone/svg:class[@name=$superClass]/@x + $fontSize*3}" y2="{$classesDone/svg:class[@name=$superClass]/@y + $fontSize*0.5}" marker-end="url(#mblack)"/>
				</xsl:for-each>
				
				<!-- Create the object that contains the current class, and that will go to the "classesDone" list -->
				<xsl:variable name="currentClassWithCoordinates">
					<class name="{$currentClassName}" x="{$x}" xEnd="{$x+string-length($currentClassName)*$fontSize*0.5}" y="{$y}"/>
				</xsl:variable>
				
				<!-- The classes to be done are those that were to be done, minus the current one, plus all subclasses of the current one.-->
				<xsl:variable name="newClassesToDo">
					<xsl:for-each select="$classesToDo/svg:class[not(@name=$currentClassName)]">
						<class name="{@name}" y="{@y}"/>
					</xsl:for-each>
					<xsl:for-each select="$subClasses">
						<xsl:variable name="subClass" select="."/>
						<xsl:if test="not($classesDone/svg:class[@name=$subClass/text()])">
						
							<!-- add this subclass only if all its superclasses have already been done. Otherwise, the subclass cannot link upwards to its superclasses with arrows. -->
							<xsl:variable name="subsuperClasses" select="key('getSuperclasses', $subClass)/s:binding[@name='o']/s:uri"/>
							<xsl:if test="count($subsuperClasses[text()=$classesDone/svg:class/@name])=count($subsuperClasses)-1">
								<class name="{.}" y="{$y+$taxonomyDistance}"/>
							</xsl:if>
						</xsl:if>
					</xsl:for-each>
				</xsl:variable>
				
				<!-- Call the template recursively -->
				<xsl:call-template name="makeTaxonomy">
					<xsl:with-param name="classesToDo" select="ex:node-set($newClassesToDo)"/>
					<xsl:with-param name="classesDone" select="$classesDone | ex:node-set($currentClassWithCoordinates)"/>
				</xsl:call-template>
			</xsl:when>
			<xsl:otherwise>			
				<!-- If there are no more classes to be done, we have to print the facts -->
				
				<!-- Scale the taxonomy to take up the upper half of the image. Find max y and max x -->				
				<xsl:variable name="maxY">
					<xsl:choose>
						<xsl:when test="$classesDone/svg:class">
							<xsl:for-each select="$classesDone/svg:class/@y">
								<xsl:sort select="." data-type="number"/>
								<xsl:if test="position()=last()">
									<xsl:value-of select=". + $taxonomyDistance"/>
								</xsl:if>
							</xsl:for-each>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="$fontSize*2"/>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				<xsl:variable name="maxX">
					<xsl:choose>
						<xsl:when test="$classesDone/svg:class">
							<xsl:for-each select="$classesDone/svg:class/@xEnd">
								<xsl:sort select="." data-type="number"/>
								<xsl:if test="position()=last()">
									<xsl:value-of select=". "/>
								</xsl:if>
							</xsl:for-each>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="$fontSize*2"/>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				
				<!-- Close the taxonomy group and the definition -->
                <xsl:text disable-output-escaping="yes">&lt;/g&gt;</xsl:text>
                <xsl:text disable-output-escaping="yes">&lt;/defs&gt;</xsl:text>
                
                <!-- Scale the taxonomy to fit into the upper half of the image -->
                <xsl:variable name="scaleX" select="$width div $maxX" />
                <xsl:variable name="scaleY" select="$height div 2 div $maxY" />
                <xsl:variable name="scale">
					<xsl:if test="$scaleY&gt;$scaleX">
				   		<xsl:value-of select="$scaleX" />
					</xsl:if>
					<xsl:if test="not($scaleY&gt;$scaleX)">
				   		<xsl:value-of select="$scaleY" />
					</xsl:if>				
                </xsl:variable>
                <xsl:variable name="shiftHelper">
					<xsl:if test="not($scaleY&gt;$scaleX)">
				   		<xsl:value-of select=" ($width - $maxX*$scaleY) div 2" />
					</xsl:if>				
                </xsl:variable>
                <xsl:variable name="shift" select="normalize-space($shiftHelper)" />
				<use href="#taxonomy" x="{$shift div $scale}" y="0" transform="scale({$scale})" />
				
				<!-- Print the entity name -->
				<xsl:variable name="x" select="$width div 2" />
				<xsl:variable name="y" select="$height div 2" />
				<text text-anchor="middle" x="{$x}" y="{$y+$fontSize}" font-size="{$fontSize}">
					<xsl:value-of select="$entity" />
				</text>
				
				<!-- Link the name to all classes that have no subclasses -->
				<xsl:variable name="taxonomy" select="/s:sparql/s:results/s:result/s:binding" />
				<!-- for unknown reasons, /s:sparql and the keys are not accessible inside the xsl:for-each, so we save it to a variable -->
				<xsl:for-each select="$classesDone/svg:class">
					<xsl:variable name="type" select="." />
					<xsl:if test="not($taxonomy[@name='o' and s:uri/text()=$type/@name])">
						<line stroke-width="{$fontSize*0.1}"  opacity="0.2" stroke="black" x1="{$x}" y1="{$y}" x2="{@x*$scale+$shift+$fontSize*3*$scale}" y2="{@y*$scale+$fontSize*0.5*$scale}" marker-end="url(#mblack)"/>
					</xsl:if>
				</xsl:for-each>
				
				<!-- Print the facts -->
				<xsl:variable name="facts" select="/s:sparql/s:results/s:result[s:binding[@name='p' and s:uri/text()!='rdfs:subClassOf']]" />
				<xsl:variable name="numberOfFacts" select="count($facts)" />
				<xsl:for-each select="$facts">
					<xsl:variable name="object" select="s:binding[@name='o']/s:uri/text() | s:binding[@name='o']/s:literal/text()" />
					<xsl:variable name="displayObject">
						<xsl:if test="string-length($object)&gt;30">
 							<xsl:value-of select="concat(substring($object,1,27),'...')"/>
						</xsl:if>
						<xsl:if test="not(string-length($object)&gt;30)">
 							<xsl:value-of select="$object"/>
						</xsl:if>						
					</xsl:variable>
					<xsl:variable name="predicate" select="s:binding[@name='p']/s:uri/text()" />
					<xsl:variable name="displayPredicate">
						<xsl:if test="string-length($predicate)&gt;20">
 							<xsl:value-of select="concat(substring($object,1,17),'...')"/>
						</xsl:if>
						<xsl:if test="not(string-length($predicate)&gt;20)">
 							<xsl:value-of select="$predicate"/>
						</xsl:if>						
					</xsl:variable>
					
					<!-- Draw the arrow -->
					<line x1="{$x+ $fontSize*string-length($entity)*0.5 div 2}" y1="{$y}" x2="{$x+$radius}" y2="{$y}" transform="rotate({180 div ($numberOfFacts + 1)*position()} {$x} {$y})" marker-end="url(#mblack)" stroke-width="{$fontSize*0.1}" stroke="black" />
					
					<!-- Treat left and right quadrant differently -->
					<xsl:choose>
						<xsl:when test="position()&lt; $numberOfFacts div 2">
							<a href="{concat($yagoUrl,$object)}"><text x="{$x+$fontSize+$radius}" y="{$y+$fontSize*0.3}" transform="rotate({180 div ($numberOfFacts + 1)*position()} {$x} {$y})" font-size="{$fontSize}"  fill="blue">
								<xsl:value-of select="$displayObject" />
								<xsl:if test="number(s:binding[@name='count']/s:literal/text())&gt;1">&#x20;(+<xsl:value-of select="number(s:binding[@name='count']/s:literal/text())-1" />)</xsl:if>
							</text></a>
							<text text-anchor="end" x="{$x+$radius - ($fontSize div 2)}" y="{$y - $fontSize*0.2}" transform="rotate({180 div ($numberOfFacts + 1)*position()} {$x} {$y})" font-size="{$fontSize}" fill="black">
								<xsl:value-of select="$displayPredicate" />
							</text>
						</xsl:when>
						<xsl:otherwise>
							<a href="{concat($yagoUrl,$object)}"><text text-anchor="end" x="{$x - $radius - $fontSize}" y="{$y+$fontSize*0.3}" transform="rotate({-180 div ($numberOfFacts + 1)*($numberOfFacts - position()+1)} {$x} {$y})" font-size="{$fontSize}"  fill="blue">
								<xsl:value-of select="$displayObject" />
							</text></a>
							<text x="{$x - $radius + $fontSize*0.5}" y="{$y - $fontSize*0.2}" transform="rotate({-180 div ($numberOfFacts + 1)*($numberOfFacts - position()+1)} {$x} {$y})" font-size="{$fontSize}"  fill="black">
								<xsl:value-of select="$displayPredicate" />
							</text>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:for-each>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<!-- Main template -->
	<xsl:template match="/">
		<svg
			xmlns="http://www.w3.org/2000/svg"
			xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 {$width} {$height}">
			<!-- We open the definitions here, and will close them in the recursive template -->
            <xsl:text disable-output-escaping="yes">&lt;defs&gt;</xsl:text>
				<marker id="mblack" markerHeight="3" markerUnits="strokeWidth" markerWidth="4" orient="auto" refX="0" refY="5" viewBox="0 0 10 10">
					<path d="M 0 0 L 10 5 L 0 10 z" fill="black" stroke="black"/>
				</marker>

			<!-- Open the taxonomy group here, we will close it in the recursive template-->
			 <xsl:text disable-output-escaping="yes">&lt;g id="taxonomy"&gt;</xsl:text>
			 
			<!-- Call the taxonomy template with a start node for "schema:Thing" -->
			<xsl:variable name="schemaThing">
				<class name="schema:Thing" y="{$fontSize}"/>
			</xsl:variable>
			<xsl:call-template name="makeTaxonomy">
				<xsl:with-param name="classesToDo" select="ex:node-set($schemaThing)"/>
				<xsl:with-param name="classesDone" select="/this/is/empty"/>
			</xsl:call-template>
		</svg>
	</xsl:template>
</xsl:stylesheet>
