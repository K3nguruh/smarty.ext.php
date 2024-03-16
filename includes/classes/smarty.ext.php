<?php
/**
 * Klasse SmartyExt
 *
 * Eine Erweiterung der Smarty Template Engine mit zusätzlichen Methoden
 * 1. Extraktion von HTML-Elementen
 *
 * Autor:   K3nguruh <k3nguruh at mail dot de>
 * Version: 1.0.1
 * Datum:   2024-03-07 21:15
 * Lizenz:  MIT-Lizenz
 */

class SmartyExt extends Smarty
{
  /**
   * Extrahiert den Inhalt eines HTML-Elements aus einem Smarty-Template anhand seiner ID.
   *
   * @param string $url Die URL des Smarty-Templates, aus dem der Inhalt extrahiert werden soll.
   * @param string $id Die ID des HTML-Elements, dessen Inhalt extrahiert werden soll.
   * @return string Der Inhalt des HTML-Elements oder ein leerer String, falls das Element nicht gefunden wurde.
   */
  public function getElementById(string $url, string $id): string
  {
    return $this->_getElement($url, "//*[@id='{$id}']");
  }

  /**
   * Extrahiert den Inhalt eines HTML-Elements aus einem Smarty-Template anhand seines Namens.
   *
   * @param string $url Die URL des Smarty-Templates, aus dem der Inhalt extrahiert werden soll.
   * @param string $name Der Name des HTML-Elements, dessen Inhalt extrahiert werden soll.
   * @return string Der Inhalt des HTML-Elements oder ein leerer String, falls das Element nicht gefunden wurde.
   */
  public function getElementByName(string $url, string $name): string
  {
    return $this->_getElement($url, "//*[@name='{$name}']");
  }

  /**
   * Extrahiert den Inhalt eines HTML-Elements aus einem Smarty-Template anhand seiner CSS-Klasse.
   *
   * @param string $url Die URL des Smarty-Templates, aus dem der Inhalt extrahiert werden soll.
   * @param string $class Die CSS-Klasse des HTML-Elements, dessen Inhalt extrahiert werden soll.
   * @return string Der Inhalt des HTML-Elements oder ein leerer String, falls das Element nicht gefunden wurde.
   */
  public function getElementByClass(string $url, string $class): string
  {
    return $this->_getElement($url, "//*[contains(concat(' ', normalize-space(@class), ' '), ' {$class} ')]");
  }

  /**
   * Extrahiert den Inhalt eines HTML-Elements aus einem Smarty-Template anhand seiner ID, seines Namens oder seiner Klasse.
   *
   * @param string $url Die URL des Smarty-Templates, aus dem der Inhalt extrahiert werden soll.
   * @param string $query Der XPath-Ausdruck zum Selektieren des HTML-Elements.
   * @return string Der Inhalt des HTML-Elements oder ein leerer String, falls das Element nicht gefunden wurde.
   */
  private function _getElement(string $url, string $query): string
  {
    // Lade das Smarty-Template mit smarty->fetch()
    $template = $this->fetch($url);

    // Erstelle ein DOMDocument-Objekt
    $dom = new DOMDocument();

    // Lade das Template mit unterdrückten Fehlermeldungen (E_WARNING),
    // da wir möglicherweise mit unvollständigem HTML / HTML5 arbeiten
    // FIX: UTF-8
    @$dom->loadHTML('<meta charset="UTF-8">' . $template);

    // Finde das Element mit der angegebenen ID, dem Namen oder der Klasse
    $xpath = new DOMXPath($dom);
    $container = $xpath->query($query)->item(0);

    // Überprüfe, ob das Container-Element gefunden wurde
    if ($container instanceof DOMElement) {
      // Erstelle ein neues DOMDocumentFragment
      $fragment = $dom->createDocumentFragment();

      // Kopiere den Inhalt des Container-Elements in das Fragment
      foreach ($container->childNodes as $node) {
        $fragment->appendChild($node->cloneNode(true));
      }

      // Konvertiere das Fragment in HTML und gib es zurück
      return $dom->saveHTML($fragment);
    } else {
      // Gib einen leeren String zurück, wenn kein Container gefunden wurde
      return "";
    }
  }
}
