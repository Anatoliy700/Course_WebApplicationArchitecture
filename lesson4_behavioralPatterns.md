### 1 Место

[Корзина](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/master/src/Service/Order/Basket.php)
 жестко привязана к сессии,
подойдет паттерн `Стратегия`, так как нет методов с общей логикой для каждой реализации паттерна, что пердусмотрено в паттерне Шаблонный метод.

[public function __construct(SessionInterface $session)](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/307de184f0f3ffad37aff23478d7ae0c322a2b5b/src/Service/Order/Basket.php#L33)
___

### 2 Место

В [корзине](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/master/src/Service/Order/Basket.php)
 реализован метод оформления заказа, его лучше вынести в отдельный класс и использовать паттерн `Команда`, это позволит ослабить связанность корзины с классами, учавствующими в оформлении заказа и реализовать DI.

[public function checkout(): void](https://github.com/Anatoliy700/Course_WebApplicationArchitecture/blob/307de184f0f3ffad37aff23478d7ae0c322a2b5b/src/Service/Order/Basket.php#L82)
