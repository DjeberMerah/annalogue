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
import static org.junit.Assert.assertFalse;
import static org.junit.Assert.assertThat;

public class RES6Test {
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
  public void rES6() {
    driver.get(Adresse.getAdresse());
    driver.findElement(By.id("mail")).click();
    driver.findElement(By.id("mail")).sendKeys("resp@univ-fcomte.fr");
    driver.findElement(By.id("password")).click();
    driver.findElement(By.id("password")).sendKeys("1234");
    driver.findElement(By.cssSelector(".btn")).click();
    assertThat(driver.findElement(By.cssSelector(".navbar-text")).getText(), is("Responsable (Responsable)"));

    driver.findElement(By.linkText("Test Fonctionnel")).click();
    driver.findElement(By.linkText("Utilisateurs inscrits")).click();
    driver.findElement(By.xpath("//button[contains(.,\'D\u00e9sinscrire\')]")).click();
    driver.findElement(By.xpath("//a[contains(text(),\'D\u00e9sinscrire\')]")).click();

    assertFalse(driver.findElement(By.xpath("/html/body/div[1]/table/tbody/tr[1]/td[1]")).getText().equals("user@univ-fcomte.fr"));
    
    driver.findElement(By.linkText("D\u00e9connexion")).click();
  }
}
