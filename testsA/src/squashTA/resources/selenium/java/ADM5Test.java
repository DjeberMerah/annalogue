// Generated by Selenium IDE
import com.gargoylesoftware.htmlunit.BrowserVersion;
import com.gargoylesoftware.htmlunit.WebClient;
import org.hamcrest.core.Is;
import org.junit.Assert;
import org.junit.Test;
import org.junit.Before;
import org.junit.After;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.CoreMatchers.is;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.Dimension;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;
import java.util.concurrent.TimeUnit;
import java.util.logging.Level;

public class ADM5Test {
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
  public void aDM5() {
    driver.get(Adresse.getAdresse());
    driver.findElement(By.id("mail")).click();
    driver.findElement(By.id("mail")).sendKeys("admin@univ-fcomte.fr");
    driver.findElement(By.id("password")).click();
    driver.findElement(By.id("password")).sendKeys("1234");
    driver.findElement(By.cssSelector(".btn")).click();
    driver.findElement(By.linkText("Utilisateurs")).click();
    driver.findElement(By.linkText("Cr\u00e9er un utilisateur")).click();
    driver.findElement(By.id("user")).click();
    driver.findElement(By.id("user_name")).click();
    driver.findElement(By.id("user_name")).sendKeys("Emile");
    driver.findElement(By.id("user_mail")).sendKeys("emile.eid@univ-fcomte.fr");
    driver.findElement(By.id("user_password_first")).click();
    driver.findElement(By.id("user_password_first")).sendKeys("1234");
    driver.findElement(By.id("user_password_second")).click();
    driver.findElement(By.id("user_password_second")).sendKeys("1234");
    {
      WebElement dropdown = driver.findElement(By.id("user_roles"));
      dropdown.findElement(By.xpath("//option[. = 'Utilisateur']")).click();
    }
    {
      WebElement dropdown = driver.findElement(By.id("user_roles"));
      dropdown.findElement(By.xpath("//option[. = 'Administrateur']")).click();
    }
    driver.findElement(By.id("user_submit")).click();
    String attendu=Adresse.getAdresse()+"users";
    String URL = driver.getCurrentUrl();
    Assert.assertEquals(URL, attendu);
    assertThat(driver.findElement(By.cssSelector(".alert")).getText(), Is.is("L'utilisateur Emile a bien \u00e9t\u00e9 cr\u00e9\u00e9."));
    assertThat(driver.findElement(By.cssSelector("tr:nth-child(4) > .align-middle:nth-child(3)")).getText(), Is.is("Emile"));
    driver.findElement(By.linkText("D\u00e9connexion")).click();
    URL = driver.getCurrentUrl();
    attendu=Adresse.getAdresse()+"login";
    Assert.assertEquals(URL, attendu);
    driver.findElement(By.id("mail")).click();
    driver.findElement(By.id("mail")).sendKeys("emile.eid@univ-fcomte.fr");
    driver.findElement(By.id("password")).click();
    driver.findElement(By.id("password")).sendKeys("1234");
    driver.findElement(By.cssSelector(".btn")).click();
    assertThat(driver.findElement(By.cssSelector(".navbar-text")).getText(), is("Emile (Administrateur)"));
    driver.findElement(By.linkText("D\u00e9connexion")).click();
  }
}
