### Видео
https://www.youtube.com/watch?v=GoDsA7VDzEI

### Инсталяция
docker-compose up -d

make composer install 

### Модель даних (ORM)

1) User:
https://github.com/aposidelov/policy_exam/blob/main/web/src/Entity/User.php
https://github.com/aposidelov/policy_exam/blob/main/web/src/Repository/UserRepository.php

2) Insurance Policy Type
https://github.com/aposidelov/policy_exam/blob/main/web/src/Entity/InsurancePolicyType.php
https://github.com/aposidelov/policy_exam/blob/main/web/src/Repository/InsurancePolicyTypeRepository.php

3) Insurance Policy
https://github.com/aposidelov/policy_exam/blob/main/web/src/Entity/InsurancePolicy.php
https://github.com/aposidelov/policy_exam/blob/main/web/src/Repository/InsurancePolicyRepository.php

4) User Policy
https://github.com/aposidelov/policy_exam/blob/main/web/src/Entity/UserPolicy.php
https://github.com/aposidelov/policy_exam/blob/main/web/src/Repository/UserPolicyRepository.php

### CRUD Контрллеры для каждой сущности
https://github.com/aposidelov/policy_exam/blob/main/web/src/Controller/InsurancePolicyController.php
https://github.com/aposidelov/policy_exam/blob/main/web/src/Controller/InsurancePolicyTypeController.php
https://github.com/aposidelov/policy_exam/blob/main/web/src/Controller/UserController.php
https://github.com/aposidelov/policy_exam/blob/main/web/src/Controller/UserPolicyController.php

### Контрллер заявки на страховое проишествие
https://github.com/aposidelov/policy_exam/blob/main/web/src/Controller/PolicyClaimController.php

### Хендлер Exception перехватчика 
https://github.com/aposidelov/policy_exam/blob/main/web/src/EventListener/ExceptionListener.php

### Пример отправки запроса-заявки в Postman
http://localhost/policy-claim/{uid}/{pid}

<img width="686" alt="Screen Shot 2023-05-26 at 10 04 51 PM" src="https://github.com/aposidelov/policy_exam/assets/2487197/913706c9-c173-403c-86de-d7f979da4b66">






