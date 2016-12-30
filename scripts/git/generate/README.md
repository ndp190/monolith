GO1 Enrolment [![build status](https://code.go1.com.au/microservices/enrolment/badges/master/build.svg)](https://code.go1.com.au/microservices/enrolment/commits/master) [![coverage report](https://code.go1.com.au/microservices/enrolment/badges/master/coverage.svg)](https://code.go1.com.au/microservices/enrolment/commits/master)
====

### Features

1. User can create enrolment on learning pathway.
    1. If the learning pathway is commercial, user will have enrolment to all sub courses.
    1. If the learning pathway is free, user will have to purchase for each inside commercial courses .
1. User can create enrolment on a course.
    1. When the status of course-enrolment is changed
        1. If user has enrolment on parent learning pathway
            1. The progress percentage of learning pathway will be recalculated.
1. User can create enrolment on a module ONLY when user has enrolment on a course
    1. If the enrolment is active, user can get a token.
        1. This token is only available in very limited time, user a fetch again if it's expired.
        1. From the token, user can load details of learning items inside the module.
    1. When the status of module-enrolment is changed
        1. The progress percentage of course will be recalculated.
1. Use can create enrolment on any learning item.
    1. When the status of LI-enrolment is changed
        1. If user has enrolment on parent module
            1. The progress percentage of module will be recalculated.
1. Portal admin can create enrolment for student on their behalf.
1. Supporting payment gateways:
    1. Stripe
    1. Credit
1. Service will broadcast on events:
    1. Events: enrolment.{created, updated, deleted}
    1. Event names: enrolment.created, enrolment.updated, enrolment.deleted


### PERMISSIONS on enrolment status

1. Student CAN manually change status of enrolment of simple LI: Text, Resource, Iframe, …
1. Student CANNOT manually change enrolment.status of complex LI: Quiz, Scorm, Tincan, …
1. Student CANNOT manually change enrolment.status of learning pathway, course, module.
1. Portal administrator can manually change enrolment.status of all enrolment.
