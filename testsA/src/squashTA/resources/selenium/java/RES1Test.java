// Generated by Selenium IDE

import com.gargoylesoftware.htmlunit.BrowserVersion;
import com.gargoylesoftware.htmlunit.WebClient;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

import java.util.concurrent.TimeUnit;
import java.util.logging.Level;

import static org.hamcrest.CoreMatchers.is;
import static org.junit.Assert.assertThat;

public class RES1Test {
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
  public void rES1() {
    driver.get(Adresse.getAdresse());
    driver.findElement(By.id("mail")).click();
    driver.findElement(By.id("mail")).sendKeys("resp@univ-fcomte.fr");
    driver.findElement(By.id("password")).click();
    driver.findElement(By.id("password")).sendKeys("1234");
    driver.findElement(By.xpath("//button[contains(.,\'Se connecter\')]")).click();
    assertThat(driver.findElement(By.cssSelector(".navbar-text")).getText(), is("Responsable (Responsable)"));
    driver.findElement(By.linkText("Cr\u00e9er un module")).click();
    driver.findElement(By.id("module_name")).click();
    driver.findElement(By.id("module_name")).sendKeys("Genie Logiciel");
    driver.findElement(By.id("module_submit")).click();

   assertThat(driver.findElement(By.xpath("/html/body/div[1]/table/tbody/tr[4]/td[1]/a")).getText(), is("Genie Logiciel"));

    assertThat(driver.findElement(By.cssSelector(".alert")).getText(), is("Le module Genie Logiciel a bien \u00e9t\u00e9 cr\u00e9\u00e9."));

    driver.findElement(By.linkText("D\u00e9connexion")).click();
  }
}
