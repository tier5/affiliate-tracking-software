
Feature('Login Test');

Scenario('test something', (I) => {
    I.amOnPage('/session/login');
    I.see('Sign In');
    I.fillField('email','zacha@reputationloop.com');
    I.fillField('password','12345678');
    I.click('.btnLink');
    I.see('Dashboard');
});
