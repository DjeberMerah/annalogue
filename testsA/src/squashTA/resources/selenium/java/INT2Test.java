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

public class INT2Test {
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
    public void tes() {
        driver.get(Adresse.getAdresse());
        driver.findElement(By.id("mail")).click();
        driver.findElement(By.id("mail")).sendKeys("resp@univ-fcomte.fr");
        driver.findElement(By.id("password")).click();
        driver.findElement(By.id("password")).sendKeys("1234");

        driver.findElement(By.cssSelector(".btn")).click();
        driver.findElement(By.linkText("Programmation d'Architecture Multi-tiers")).click();

        assertThat(driver.findElement(By.cssSelector("tr:nth-child(1) > .align-middle:nth-child(3)")).getText(), is("TD"));

        driver.findElement(By.linkText("Modifier")).click();
        driver.findElement(By.id("subject_description")).click();
        driver.findElement(By.id("subject_description")).sendKeys("descr");
        driver.findElement(By.id("subject_type")).click();
        {
            WebElement dropdown = driver.findElement(By.id("subject_type"));
            dropdown.findElement(By.xpath("//option[. = 'Cours']")).click();
        }
        driver.findElement(By.cssSelector("option:nth-child(1)")).click();
        driver.findElement(By.id("subject_submit")).click();

        assertThat(driver.findElement(By.cssSelector("tr:nth-child(1) > .align-middle:nth-child(3)")).getText(), is("Cours"));
        driver.findElement(By.linkText("D\u00e9connexion")).click();
    }
}