// Generated by Selenium IDE
import com.gargoylesoftware.htmlunit.BrowserVersion;
import com.gargoylesoftware.htmlunit.WebClient;
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

public class ADM1NP1Test {
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
  public void aDM1NP1() {
    driver.get(Adresse.getAdresse());
    //driver.manage().window().setSize(new Dimension(1346, 940));
    driver.findElement(By.id("mail")).click();
    driver.findElement(By.id("mail")).sendKeys("admin@univ-fcomte.fr");
    driver.findElement(By.id("password")).click();
    driver.findElement(By.id("password")).sendKeys("1234");
    driver.findElement(By.cssSelector(".btn")).click();
    driver.findElement(By.linkText("Utilisateurs")).click();
    driver.findElement(By.linkText("Cr\u00e9er un utilisateur")).click();
    driver.findElement(By.id("user_name")).click();
    driver.findElement(By.id("user_name")).sendKeys("Admin");
    driver.findElement(By.id("user_mail")).click();
    driver.findElement(By.id("user_mail")).sendKeys("admin@univ-fcomte.fr");
    driver.findElement(By.id("user_password_first")).click();
    driver.findElement(By.id("user_password_first")).sendKeys("1234");
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
    assertThat(driver.findElement(By.cssSelector(".form-error-message")).getText(), is("Cette valeur est d\u00e9j\u00e0 utilis\u00e9e."));
    driver.findElement(By.linkText("D\u00e9connexion")).click();
  }
}
