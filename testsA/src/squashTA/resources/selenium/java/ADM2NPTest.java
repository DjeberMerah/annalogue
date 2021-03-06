// Generated by Selenium IDE

import com.gargoylesoftware.htmlunit.BrowserVersion;
import com.gargoylesoftware.htmlunit.WebClient;
import org.junit.After;
import org.junit.Assert;
import org.junit.Before;
import org.junit.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.util.List;
import java.util.concurrent.TimeUnit;
import java.util.logging.Level;

public class ADM2NPTest {
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
  public void aDM2NP() {
    driver.get(Adresse.getAdresse());
    driver.findElement(By.id("mail")).click();
    driver.findElement(By.id("mail")).sendKeys("user@univ-fcomte.fr");
    driver.findElement(By.id("password")).click();
    driver.findElement(By.id("password")).sendKeys("1234");
    driver.findElement(By.cssSelector(".btn")).click();
    {
      List<WebElement> elements = driver.findElements(By.linkText("Utilisateurs"));
      assert(elements.size() == 0);
    }
    String URL = driver.getCurrentUrl();
    Assert.assertEquals(URL, Adresse.getAdresse());
    driver.findElement(By.linkText("D\u00e9connexion")).click();
  }
}
