import puppeteer from 'puppeteer';

(async () => {
  const url = 'http://127.0.0.1:8000/';
  const browser = await puppeteer.launch({ args: ['--no-sandbox','--disable-setuid-sandbox'] });
  const page = await browser.newPage();

  page.on('console', msg => {
    console.log(`BROWSER_CONSOLE [${msg.type()}]: ${msg.text()}`);
  });

  page.on('pageerror', err => {
    console.log(`BROWSER_PAGEERROR: ${err.toString()}`);
  });

  page.on('requestfailed', req => {
    const f = req.failure();
    console.log(`REQUEST_FAILED: ${req.url()} => ${f && f.errorText ? f.errorText : 'unknown'}`);
  });

  page.on('response', async res => {
    try {
      const req = res.request();
      if ((req.resourceType() === 'script' || req.resourceType() === 'document') || res.url().includes('/build/')) {
        console.log(`RESPONSE: ${res.status()} ${res.url()}`);
      }
    } catch (e) {
      console.error('response handler error', e);
    }
  });

  console.log('Navigating to', url);
  await page.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });

  // Capture some HTML snapshot
  const html = await page.content();
  console.log('PAGE_HTML_START');
  console.log(html.slice(0, 2000));
  console.log('PAGE_HTML_END');

  // Take screenshot as extra evidence
  await page.screenshot({path: 'capture.png', fullPage: true}).catch(()=>{});

  await browser.close();
})();
