// Generated by Selenium IDE

import com.gargoylesoftware.htmlunit.BrowserVersion;
import com.gargoylesoftware.htmlunit.WebClient;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.util.List;
import java.util.concurrent.TimeUnit;
import java.util.logging.Level;

import static org.hamcrest.CoreMatchers.is;
import static org.junit.Assert.assertThat;

public class ADM3PTest {
  private WebDriver driver;
  @Before
  public void setUp() {
    driver = new HtmlUnitDriver(true) {
      @Override
      protected WebClient newWebClient(BrowserVersion version) {
        WebClient webClient = super.newWebClient(version);
        webClient.getOptions().setThrowExceptionOnScriptError(false);
        return webClient;
      }
    };
    driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
    java.util.logging.Logger.getLogger("com.gargoylesoftware").setLevel(Level.OFF);
    System.setProperty("org.apache.commons.logging.Log", "org.apache.commons.logging.impl.NoOpLog");
  }
  @After
  public void tearDown() {
    driver.quit();
  }
  @Test
  public void aDM3P() {
    driver.get(Adresse.getAdresse());
    driver.findElement(By.id("mail")).click();
    driver.findElement(By.id("mail")).sendKeys("admin@univ-fcomte.fr");
    driver.findElement(By.id("password")).click();
    driver.findElement(By.id("password")).sendKeys("1234");
    driver.findElement(By.cssSelector(".btn")).click();

    assertThat(driver.findElement(By.cssSelector(".navbar-text")).getText(), is("Admin (Administrateur)"));

    driver.findElement(By.linkText("Test Fonctionnel")).click();
    driver.findElement(By.linkText("Utilisateurs inscrits")).click();
    driver.findElement(By.linkText("Rendre intervenant")).click();
    {
      List<WebElement> elements = driver.findElements(By.cssSelector(".alert"));
      assert(elements.size() > 0);
    }
    {
      List<WebElement> elements = driver.findElements(By.xpath("(//a[contains(text(),\'Annuler intervenant\')])[2]"));
      assert(elements.size() > 0);
    }
    driver.findElement(By.linkText("D\u00e9connexion")).click();
  }
}
