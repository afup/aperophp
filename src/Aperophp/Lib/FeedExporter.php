<?php

namespace Aperophp\Lib;

class FeedExporter
{
    /**
     * @param array $drinks
     * @return string
     */
    public function export(array $drinks, \DateTime $exportDate = null)
    {
        if (null === $exportDate) {
            $exportDate = new \DateTime();
        }

        $document = new \DOMDocument();
        $feed = $document->createElement('feed');
        $feed->setAttribute('xmlns', 'http://www.w3.org/2005/Atom');

        $this
            ->apppendNewChild($document, $feed, 'title', 'AperoPHP')
            ->apppendNewChild($document, $feed, 'subtitle', 'Liste des apéro PHP à venir')
            ->apppendNewChild($document, $feed, 'link', null, array('href' => 'http://aperophp.net'))
            ->apppendNewChild($document, $feed, 'updated', $exportDate->format('c'))
            ->appendChild($feed, ($author = $document->createElement('author')))
                ->apppendNewChild($document, $author, 'name', 'AFUP')
                ->apppendNewChild($document, $author, 'email', 'contact@afup.org')
            ->apppendNewChild($document, $feed, 'id', 'http://aperophp.net/')
        ;

        foreach ($drinks as $drink) {
            $url = sprintf("http://aperophp.net/%d/view.html", $drink['id']);
            $title = sprintf('%s le %s', $drink['city_name'], (new \DateTime($drink['day']))->format('d/m/Y à H:i'));

            $this
                ->appendChild($feed, ($entry = $document->createElement('entry')))
                    ->apppendNewChild($document, $entry, 'title', $title)
                    ->apppendNewChild($document, $entry, 'id', $url)
                    ->apppendNewChild($document, $entry, 'link', null, array('href' => $url))
                    ->apppendNewChild($document, $entry, 'updated', (new \DateTime($drink['updated_at']))->format('c'))
                    ->apppendNewChild($document, $entry, 'summary', $drink['description'], array('type' => 'html'))
            ;
        }

        $document->appendChild($feed);

        return $document->saveXML();
    }

    /**
     * @param \DOMDocument $document
     * @param \DOMNode $node
     * @param string $name
     * @param string|null $value
     * @param array $attributes
     *
     * @return $this
     */
    protected function apppendNewChild(\DOMDocument $document, \DOMNode $node, $name, $value = null, array $attributes = array())
    {
        $element = $document->createElement($name, $value);
        foreach ($attributes as $attributeName => $attributeValue) {
            $element->setAttribute($attributeName, $attributeValue);
        }

        $this->appendChild($node, $element);

        return $this;
    }

    /**
     * @param \DOMNode $parent
     * @param \DOMNode $child
     *
     * @return $this
     */
    protected function appendChild(\DOMNode $parent, \DOMNode $child)
    {
        $parent->appendChild($child);

        return $this;
    }

}
