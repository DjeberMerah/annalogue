// Generated by Selenium IDE
import com.gargoylesoftware.htmlunit.BrowserVersion;
import com.gargoylesoftware.htmlunit.WebClient;
import org.junit.Assert;
import org.junit.Test;
import org.junit.Before;
import org.junit.After;
import static org.junit.Assert.*;
import static org.hamcrest.CoreMatchers.is;
import static org.hamcrest.core.IsNot.not;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.Dimension;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.Alert;
import org.openqa.selenium.Keys;
import java.util.*;
import java.util.concurrent.TimeUnit;
import java.util.logging.Level;

public class ADM6Test {
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
  public void aDM6() {
    driver.get(Adresse.getAdresse());
    driver.findElement(By.id("mail")).sendKeys("admin@univ-fcomte.fr");
    driver.findElement(By.id("password")).click();
    driver.findElement(By.id("password")).sendKeys("1234");
    driver.findElement(By.cssSelector(".btn")).click();
    driver.findElement(By.linkText("Utilisateurs")).click();
    driver.findElement(By.cssSelector("tr:nth-child(1) .text-right > .btn-danger")).click();
    driver.findElement(By.linkText("Supprimer")).click();
    {
      List<WebElement> elements = driver.findElements(By.linkText("Supprimer"));
      assert(elements.size() > 0);
    }
    String attendu=Adresse.getAdresse()+"users";
    String URL = driver.getCurrentUrl();
    Assert.assertEquals(URL, attendu);
    assertThat(driver.findElement(By.cssSelector(".alert")).getText(), is("L'utilisateur Basic user a bien \u00e9t\u00e9 supprim\u00e9."));
    driver.findElement(By.linkText("D\u00e9connexion")).click();
  }
}
