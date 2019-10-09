<?xml version="1.0" encoding="UTF-8"?>
<!-- This file contains common methods and definitions for the graph viewers -->
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
	<xsl:variable name="radius" select="500"/>
	<xsl:variable name="width" select="$radius*4"/>
	<xsl:variable name="height" select="$radius*4"/>
	<xsl:variable name="maxEntityDisplayLength" select="40"/>
	<xsl:variable name="maxPredicateDisplayLength" select="20"/>
	<xsl:variable name="yagoUrl" select="'http://yago.r2.enst.fr/graph/'"/>

	<!-- Prints a string, truncated if necessary-->
	<xsl:template name="printString">
		<xsl:param name="object" />
		<xsl:param name="length" select="$maxPredicateDisplayLength"/>
		<xsl:if test="string-length($object)&gt;$length">
			<title><xsl:value-of select="$object" /></title>
			<xsl:value-of select="substring($object,1,$length - 3)"/>...		
		</xsl:if>
		<xsl:if test="not(string-length($object)&gt;$length)">
			<xsl:value-of select="$object"/>
		</xsl:if>
	</xsl:template>

	<!-- Prints an object from a SPARQL result binding -->
	<xsl:template name="printObject">
		<xsl:param name="object" />
		<xsl:param name="length" select="$maxPredicateDisplayLength" />
		<xsl:param name="more" />
		<xsl:param name="moreUrl" />
		<xsl:variable name="entityObject" select="$object/s:uri" />
		<xsl:variable name="stringObject" select="$object/s:literal" />
		<xsl:if test="$stringObject" >
			"<xsl:call-template name="printString">
				<xsl:with-param name="object" select="$stringObject"/>
				<xsl:with-param name="length" select="$length"/>
			</xsl:call-template>"<xsl:if test="$object/s:literal/@xml:lang">@<xsl:value-of select="$object/s:literal/@xml:lang"/></xsl:if>
			<xsl:if test="$object/s:literal/@datatype"><tspan style="font-size:80%; " dy="-0.3em">^^<xsl:value-of select="$object/s:literal/@datatype" /></tspan></xsl:if>
		</xsl:if>
		<xsl:if test="$entityObject">
			<a href="{concat($yagoUrl,$object)}"  style="fill:blue">
				<xsl:call-template name="printString">
					<xsl:with-param  name="object" select="$entityObject"/>
					<xsl:with-param name="length" select="$length"/>
				</xsl:call-template>
			</a>
		</xsl:if>
		<xsl:if test="$more and $more&gt;1">
			<a href="{$moreUrl}" style="fill:blue">&#8239;&#8239;&#8239;&#8239;(+
				<xsl:value-of select="number($more)-1" />)	
			</a>
		</xsl:if>
	</xsl:template>
</xsl:stylesheet>