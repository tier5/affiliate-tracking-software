
Feature('Login Test');

Scenario('Test Login as Super Admin and See Correct Page', (I) => {
    I.amOnPage('/session/login');
    I.see('Sign In');
    I.fillField('email','zacha@reviewvelocity.co');
    I.fillField('password','12345678');
    I.click('.btnLink');
    I.see('Businesses');
    I.see('Total Active');
    I.dontSee('Location:');
    I.amOnPage('/');
    I.dontSeeInCurrentUrl('/agency');
    I.dontSeeInCurrentUrl('/admindashboard');
});

Scenario('I See Correct Page', (I) => {
    I.amOnPage('/session/login');
    I.see('Sign In');
    I.fillField('email', 'zacha@reviewvelocity.co');
    I.fillField('password', '12345678');
    I.click('.btnLink');
    I.see('Businesses');
    I.see('Total Active');
    I.dontSee('Location:');
    I.amOnPage('/');
    I.dontSeeInCurrentUrl('/agency');
    I.dontSeeInCurrentUrl('/admindashboard');
});
