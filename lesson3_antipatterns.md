###Подходит больше антипаттерн `Фактор невероятности`

[Product.php](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/master/src/Model/Entity/Product.php)

[Role.php](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/master/src/Model/Entity/Role.php)

[User.php](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/master/src/Model/Entity/User.php)

В моделях Entity не хватает валидации данных, либо она должна быть в моделях, которые создают данные модели перед передачей данных в конструктор.
___
 
 ###Подходит больше антипаттерн `Божественный объект или Членовредительство`
 
 [private function getDataFromSource(array $search = [])](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/cc3ed9ae6b69a051471110f36ebce3fcffb7f771/src/Model/Repository/Product.php#L53)
 
  Можно вынести потучение данных в отдельный класс, который реализует определенный интерфейс, что была возможность легко менять источник данных. Либо [классы репозитории](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/tree/master/src/Model/Repository) 
  должны реализовывать итерфейс, который будет описывать получение данных и минимально необходимую, требующую индивидуальной реализации для каждого источника данных, логику.
  ___
  
   ###Подходит больше антипаттерн `не удалось подобрать` нарушен Принцип инверсии зависимостей
  
  [public function process(Model\Entity\User $user, string $templateName, array $params = []): void](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/cc3ed9ae6b69a051471110f36ebce3fcffb7f771/src/Service/Communication/Email.php#L14)

Вместо `Model\Entity\User` лучше использовать интерфейс, что бы не было лишней связанности 
___

  ###Подходит больше антипаттерн `Божественный объект` нарушен Принцип единственной ответственности

Мне кажется класс
[Basket.php](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/master/src/Service/Order/Basket.php)
немного перегружен.

Я бы сделал отдельный класс-репозиторий наслудующий определенный интерфейс для корзины, так как корзина может храниться не обязательно в сессии.

[public function getProductsInfo(): array](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/cc3ed9ae6b69a051471110f36ebce3fcffb7f771/src/Service/Order/Basket.php#L71)

[    protected function getProductRepository(): Model\Repository\Product](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/cc3ed9ae6b69a051471110f36ebce3fcffb7f771/src/Service/Order/Basket.php#L132)

Не думаю что корзине нужно знать больше чем об id товаров и их количестве.


[public function checkout(): void](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/cc3ed9ae6b69a051471110f36ebce3fcffb7f771/src/Service/Order/Basket.php#L82)

[public function checkoutProcess](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/cc3ed9ae6b69a051471110f36ebce3fcffb7f771/src/Service/Order/Basket.php#L107)

Так же не думаю что оформление заказа относится к корзине
___